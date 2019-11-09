<?php

namespace YOOtheme;

use YOOtheme\Asset\FilterManager;
use YOOtheme\Util\Str;

class AssetManager implements \IteratorAggregate
{
    /**
     * @var AssetFactory
     */
    protected $factory;

    /**
     * @var AssetCollection
     */
    protected $registered;

    /**
     * @var FilterManager
     */
    protected $filters;

    /**
     * @var array
     */
    protected $queued = [];

    /**
     * @var array
     */
    protected $lazy = [];

    /**
     * @var array
     */
    protected $combine = [];

    /**
     * @var string
     */
    protected $cache;

    /**
     * Constructor.
     *
     * @param AssetFactory  $factory
     * @param FilterManager $filters
     * @param string        $cache
     */
    public function __construct(AssetFactory $factory, FilterManager $filters = null, $cache = null)
    {
        $this->factory    = $factory;
        $this->filters    = $filters ?: new FilterManager();
        $this->registered = new AssetCollection();
        $this->cache      = $cache;
    }

    /**
     * Add shortcut.
     *
     * @see add()
     */
    public function __invoke($name, $source = null, $dependencies = [], $options = [])
    {
        return $this->add($name, $source, $dependencies, $options);
    }

    /**
     * Gets a registered asset.
     *
     * @param  string $name
     * @return Asset
     */
    public function get($name)
    {
        return $this->registered->get($name);
    }

    /**
     * Adds a registered asset or a new asset to the queue.
     *
     * @param  string $name
     * @param  mixed  $source
     * @param  array  $dependencies
     * @param  array  $options
     * @return self
     */
    public function add($name, $source = null, $dependencies = [], $options = [])
    {
        if ($source !== null) {
            $this->registered->add($asset = $this->factory->create($name, $source, $dependencies, $options));
        } else {
            $asset = $this->registered->get($name);
        }

        if ($asset) {
            $this->queued[$name] = true;
        }

        return $this;
    }

    /**
     * Removes an asset from the queue.
     *
     * @param  string|array $name
     * @return self
     */
    public function remove($name)
    {
        $names = (array) $name;

        foreach ($names as $name) {
            unset($this->queued[$name]);

            foreach($this->lazy as &$dependencies) {
                if (false !== $index = array_search($name, $dependencies)) {
                    unset($dependencies[$index]);
                }
            }
        }

        return $this;
    }

    /**
     * Removes all assets from the queue.
     *
     * @return self
     */
    public function removeAll()
    {
        $this->queued = [];
        $this->lazy = [];

        return $this;
    }

    /**
     * Registers an asset.
     *
     * @param  string $name
     * @param  mixed  $source
     * @param  array  $dependencies
     * @param  array  $options
     * @return self
     */
    public function register($name, $source, $dependencies = [], $options = [])
    {
        $this->registered->add($asset = $this->factory->create($name, $source, $dependencies, $options));

        foreach ($asset->getDependencies() as $dependency) {
            if ($dependency[0] === '~') {
                $this->lazy[ltrim($dependency, '~')][] = $name;
            }
        }

        return $this;
    }

    /**
     * Unregisters an asset.
     *
     * @param  string $name
     * @return self
     */
    public function unregister($name)
    {
        $this->registered->remove($name);
        $this->remove($name);

        return $this;
    }

    /**
     * Combines a assets to a file and applies filters.
     *
     * @param  string $name
     * @param  string $pattern
     * @param  array  $filters
     * @return self
     */
    public function combine($name, $pattern, $filters = [])
    {
        $this->combine[$name] = compact('pattern', 'filters');

        return $this;
    }

    /**
     * Gets queued assets with resolved dependencies, optionally all registered assets.
     *
     * @param  bool $registered
     * @return AssetCollection
     */
    public function all($registered = false)
    {
        if ($registered) {
            return $this->registered;
        }

        $assets = [];

        foreach (array_keys($this->queued) as $name) {
            $this->resolveDependencies($this->registered->get($name), $assets);
        }

        $assets = new AssetCollection($assets);

        foreach ($this->combine as $name => $options) {
            $assets = $this->doCombine($assets, $name, $options);
        }

        return $assets;
    }

    /**
     * Resolves asset dependencies.
     *
     * @param  AssetInterface   $asset
     * @param  AssetInterface[] $resolved
     * @param  AssetInterface[] $unresolved
     * @return AssetInterface[]
     * @throws \RuntimeException
     */
    public function resolveDependencies($asset, &$resolved = [], &$unresolved = [])
    {
        $name = $asset->getName();
        $unresolved[$name] = $asset;

        foreach ($asset->getDependencies() as $dependency) {

            if ($dependency[0] === '~' && !isset($resolved[$dependency = ltrim($dependency, '~')])) {
                continue;
            }

            if (!isset($resolved[$dependency])) {

                if (isset($unresolved[$dependency])) {
                    throw new \RuntimeException(sprintf('Circular asset dependency "%s > %s" detected.', $name, $dependency));
                }

                if ($d = $this->registered->get($dependency)) {
                    $this->resolveDependencies($d, $resolved, $unresolved);
                }
            }
        }

        $resolved[$name] = $asset;
        unset($unresolved[$name]);

        if (isset($this->lazy[$name])) {
            foreach($this->lazy[$name] as $dependency) {
                if ($d = $this->registered->get($dependency)) {
                    $this->resolveDependencies($d, $resolved, $unresolved);
                }
            }
        }

        return $resolved;
    }

    /**
     * IteratorAggregate interface implementation.
     */
    public function getIterator()
    {
        return $this->all()->getIterator();
    }

    /**
     * Combines assets matching a pattern to a single file asset, optionally applies filters.
     *
     * @param  AssetCollection $assets
     * @param  string          $name
     * @param  array           $options
     * @return AssetCollection
     */
    protected function doCombine(AssetCollection $assets, $name, $options = [])
    {
        extract($options);

        $combine = new AssetCollection();

        foreach ($assets as $asset) {
            if (Str::is($pattern, $asset->getName())) {
                $combine->add($asset);
            }
        }

        $file = strtr($this->cache, ['%name%' => $name]);

        if ($names = $combine->names() and $file = $this->doCache($combine, $file, $filters)) {
            $assets->remove(array_slice($names, 1));
            $assets->replace(array_shift($names), $this->factory->create($name, $file));
        }

        return $assets;
    }

    /**
     * Writes an asset collection to a cache file, optionally applies filters.
     *
     * @param  AssetCollection $assets
     * @param  string          $file
     * @param  array           $filters
     * @return mixed
     */
    protected function doCache(AssetCollection $assets, $file, $filters = [])
    {
        $filters = $this->filters->get($filters);

        if ($this->cache && count($assets)) {

            $salt = array_merge([$_SERVER['SCRIPT_NAME']], array_keys($filters));
            $file = preg_replace('/(.*?)(\.[^\.]+)?$/i', '$1-'.$assets->hash(implode(',', $salt)).'$2', $file, 1);

            if (file_exists($file) || @file_put_contents($file, $assets->dump($filters))) {
                return $file;
            }
        }

        return false;
    }
}

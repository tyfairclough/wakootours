<?php
/**
 * @version     1.0.0
 * @package     com_modern_tours
 * @copyright   Copyright (C) 2015. All rights reserved. Unikalus team.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Jonas Jovaisas <jonasjov2@gmail.com> - http://modernjoomla.com
 */
// No direct access
defined('_JEXEC') or die;
require_once(JPATH_COMPONENT . '/helpers/modern_tours.php');

class timeTable
{

    public function __construct($startingHours = 6, $endingHour = 22, $customHours = 0, $division = 1, $weekDays = 7, $data = '')
    {
        $days = Modern_toursFrontendHelper::getDays();
        $this->startingHours = $startingHours;
        $this->endingHour = $endingHour;
        $this->weekDays = $weekDays;
        $this->minutes = 60 / $division;
        $this->customHours = $customHours;
        $this->division = $division;
        $this->totalHours = $endingHour - $startingHours;
        $this->stop = $division - 1;
        $this->monday = $days['start'];
        $this->today = date("Y-m-d");
        $this->width = floor(100 / ($this->weekDays + $this->division));
        $this->month = date("F", strtotime($days['start']));
        $this->unavailable = array();
        $this->timetable = array();
        $this->reserved = $this->getReservations();
        $this->filters = JFactory::getDbo()->setQuery('SELECT * FROM #__modern_tours_conditions')->loadObjectList();
    }

    public function generateHours()
    {
        $date = date($this->startingHours . ":00:00");
        for ($i = 0; $i <= $this->totalHours; $i++) {
            $time[] = date('H:i:s', strtotime('+' . $i . ' hour', strtotime($date)));
        }
        if ($this->division == 1) {
            return $time;
        } else {
            $hours = array_flip($time);
            $x = 0;
            foreach ($hours as $hour => $val) {
                // PRIDEDAMAS PAPILDOMAS LAIKAS
                for ($z = 0; $z < $this->division; $z++) {
                    // JEI PIRMAS CIKLAS NEPRIDETI LAIKO, JEI NE PIRMAS - PRIDETI
                    if ($z <= $this->stop AND $z !== 0) {
                        $hour = date('H:i:s', strtotime('+' . $this->minutes . ' minutes', strtotime($hour)));
                    }
                    $array[$x] = $hour;
                    $x++;
                }
                $z = 0;
            }
            array_splice($time, -$this->stop);
        }

        return $time;
    }

    public function customTime($hours = array())
    {
        $this->customHours = $hours;
    }

    public function addTimes()
    {
        $hours = $this->generateHours();
        for ($i = 0; $i < count($hours); $i++) {
            $timeslots[$hours[$i]] = array('status' => 'date', 'reservations' => 0, 'class' => 'time', 'text' => $hours[$i]);
        }

        return $timeslots;
    }

    // GENERATE DAYS OF WEEK I.E 2015-12-12
    public function generateWeek()
    {
        for ($i = 0; $i < $this->weekDays; $i++) {
            $days[] = date('Y-m-d', strtotime('+' . $i . ' day', strtotime($this->monday)));
        }

        return $days;
    }

    // GENERATE ARRAY WITH DATES AND TIMES
    public function generateTimetable()
    {
        $hours = $this->generateHours();
        $week = $this->generateWeek();
        foreach ($week as $day) {
            for ($i = 0; $i < count($hours); $i++) {
                $class = 0;
                $timeslots[$day][$hours[$i]] = array('status' => 'Available', 'date' => $day . ' ' . $hours[$i], 'reservations' => 0, 'class' => 'block', 'text' => 'Random text {slots} and {price}');
            }
        }

        $this->timetable = $timeslots;
    }

    // ADD UNAVAILABLE FOR TIMESLOTS BEHIND TODAY
    public function blockPast()
    {
        foreach ($this->timetable as $day => $hours) {
            foreach ($hours as $hour => $x) {
                if ($day < $this->today) {
                    $this->timetable[$day][$hour] = 2;
                }
            }
        }
    }

    public function textGenerate($with = array())
    {
        foreach ($this->timetable as $key => $date)
        {
            foreach ($date as $time => $timeslot)
            {
                $message = Modern_toursFrontendHelper::getParams()->slottext;
                $slots = Modern_toursFrontendHelper::getParams()->slots;
                $slots_available = $slots - $timeslot['reservations'];
                $fields['slots'] = $slots_available;
                $text = ($slots <= $timeslot['reservations'] ? 1 : 0);
                $generator = new Modern_toursSlotgenerator($timeslot['date'], $this->filters);
                $fields['price'] = $generator->generateCodes();
                foreach ($fields as $keys => $param) {
                    $replace[] = '/{' . $keys . '}/';
                    $with[] = $param;
                }
                $message = preg_replace($replace, $with, $message);
                $this->timetable[$key][$time]['text'] = $message;
                $replace = array();
                $with = array();
            }
        }
        $timeRow = $this->addTimes();
        $arr = array('08' => $timeRow);
        $this->timetable = $arr + $this->timetable;

        return $this->timetable;

    }

    public function generateTable()
    {
        $this->textGenerate();
    }

    public function addReserved()
    {
        $this->timetable = array_replace_recursive($this->timetable, $this->reserved);
    }

    public function addUnavailable()
    {
        $this->timetable = array_replace_recursive($this->timetable, $this->unavailable);
    }

    public function setUnavailableWeek($unavailable = array())
    {
        $this->timetable = array_replace_recursive($this->timetable, $unavailable);
    }

    public function setUnavailable($unavailable = array())
    {
        $this->unavailable = $unavailable;
    }

    public function generateCss()
    {
        //return '<style> .tr { width: ' . $this->width . '%; } </style>';
        return '<style> .trx { width: 12.5%; } </style>';
    }

    public function translate($word)
    {
        if ($word === 2) {
            $text = JText::_('BOOKINGS_MODERN_UNAVAILABLE');
        } elseif ($word === 1) {
            $text = JText::_('BOOKINGS_MODERN_RESERVED');
        } else {
            $text = JText::_('BOOKINGS_MODERN_AVAILABLE');
        }

        return $text;
    }

    public function daytranslate($day)
    {
        switch ($day) {
            case 'Monday':
                $day = JText::_('MONDAY');
                break;
            case 'Tuesday':
                $day = JText::_('Tuesday');
                break;
            case 'Wednesday':
                $day = JText::_('Wednesday');
                break;
            case 'Thursday':
                $day = JText::_('Thursday');
                break;
            case 'Friday':
                $day = JText::_('Friday');
                break;
            case 'Saturday':
                $day = JText::_('Saturday');
                break;
            case 'Sunday':
                $day = JText::_('Sunday');
                break;
        }

        return $day;
    }

    public function monthtranslate($month)
    {
        switch ($month) {
            case 'February':
                $month = JText::_('February');
                break;
            case 'January':
                $month = JText::_('January');
                break;
            case 'March':
                $month = JText::_('March');
                break;
            case 'April':
                $month = JText::_('April');
                break;
            case 'May':
                $month = JText::_('May');
                break;
            case 'June':
                $month = JText::_('June');
                break;
            case 'July':
                $month = JText::_('July');
                break;
            case 'August':
                $month = JText::_('August');
                break;
            case 'September':
                $month = JText::_('September');
                break;
            case 'October':
                $month = JText::_('October');
                break;
            case 'November':
                $month = JText::_('November');
                break;
            case 'December':
                $month = JText::_('December');
                break;
        }

        return $month;
    }

    public function translateStatus($status)
    {
        if ($status == 2) {
            $text = 'Unavailable';
        } elseif ($status == 1) {
            $text = 'Reserved';
        } else {
            $text = 'Available';
        }

        return $text;

    }

    public function getReservations()
    {
        $model = JModelList::getInstance('Calendar', 'modern_toursModel');

        return $model->getReserved();
    }
}
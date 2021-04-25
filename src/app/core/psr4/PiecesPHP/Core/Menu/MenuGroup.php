<?php

/**
 * MenuGroup.php
 */

namespace PiecesPHP\Core\Menu;

use Form\Validator;
use PiecesPHP\Core\HTML\HtmlElement;

/**
 * MenuGroup
 *
 * @category    HTML
 * @package     PiecesPHP\Core\Menu
 * @author      Vicsen Morantes <sir.vamb@gmail.com>
 * @copyright   Copyright (c) 2018
 */
class MenuGroup
{
    /**
     * @var string|null
     */
    protected $routeName;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $icon;
    /**
     * @var boolean
     */
    protected $current;
    /**
     * @var boolean
     */
    protected $visible;
    /**
     * @var boolean
     */
    protected $asLink;
    /**
     * @var string
     */
    protected $href;
    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var MenuItem[]
     */
    protected $items;
    /**
     * @var MenuGroup[]
     */
    protected $groups;
    /**
     * @var int
     */
    protected $position;
    /**
     * @var boolean
     */
    protected $show_text;
    /**
     * @var boolean
     */
    protected $show_icon;
    /**
     * @var boolean
     */
    protected $menu_direction;
    /**
     * @var array
     */
    protected $structureOptions = [
        'name' => [
            'rules' => ['is_string'],
            'default' => 'Group...',
        ],
        'icon' => [
            'rules' => ['is_string'],
            'default' => '',
        ],
        'current' => [
            'rules' => ['bool'],
            'default' => null,
        ],
        'visible' => [
            'rules' => ['bool'],
            'default' => true,
        ],
        'asLink' => [
            'rules' => ['bool'],
            'default' => false,
        ],
        'menu_direction' => [
            'rules' => ['is_string'],
            'default' => 'down',
        ],
        'href' => [
            'rules' => ['is_string'],
            'default' => '',
        ],
        'attributes' => [
            'rules' => [
                'is_array',
            ],
            'default' => [],
        ],
        'items' => [
            'rules' => ['is_array'],
            'default' => [],
        ],
        'groups' => [
            'rules' => ['is_array'],
            'default' => [],
        ],
        'routeName' => [
            'rules' => ['is_string'],
            'default' => null,
        ],
        'position' => [
            'rules' => ['integer'],
            'default' => -1,
        ],
        'show_text' => [
            'rules' => ['bool'],
            'default' => true,
        ],
        'show_icon' => [
            'rules' => ['bool'],
            'default' => false,
        ],
        'as_row' => [
            'rules' => ['bool'],
            'default' => false,
        ],
    ];

    /**
     * @param array $options
     * @param string $options['name']
     * @param string $options['icon']
     * @param bool $options['current']
     * @param bool $options['visible']
     * @param bool $options['asLink']
     * @param string $options['href']
     * @param array $options['attributes']
     * @param MenuItem[] $options['items']
     * @param MenuGroup[] $options['groups']
     * @param int $options['position']
     * @return static
     */
    public function __construct($options = [])
    {

        foreach ($this->structureOptions as $name => $config) {

            $defined_in_options = isset($options[$name]);

            if ($defined_in_options) {

                $value_on_option = $options[$name];
                $pattern_validation = [
                    $name => $config['rules'],
                ];
                $validator = new Validator($pattern_validation);
                $valid = $validator->validate([$name => $value_on_option]);

                if ($valid) {
                    if ($name == 'attributes') {
                        foreach ($value_on_option as $key => $value) {
                            $valid_attr = true;

                            if (is_string($key)) {
                                if (!is_scalar($value)) {
                                    if (is_array($value)) {
                                        foreach ($value as $jvalues) {
                                            if (!is_scalar($jvalues)) {
                                                $valid_attr = false;
                                                break;
                                            }
                                        }
                                        if ($valid_attr) {
                                            $value = implode(' ', $value);
                                        }
                                    } else {
                                        $valid_attr = false;
                                    }
                                }
                            } else {
                                $valid_attr = false;
                            }

                            if ($valid_attr) {
                                $value_on_option[$key] = (string) $value;
                            } else {
                                unset($value_on_option[$key]);
                            }
                        }
                    }

                    if ($name == 'items') {

                        $this->items = [];

                        foreach ($value_on_option as $key => $value) {
                            $valid_item = $this->validateItem($value);
                            if (!$valid_item || !$value->isVisible()) {
                                unset($value_on_option[$key]);
                            }
                        }

                        foreach ($value_on_option as $key => $value) {
                            $this->addItem($value);
                        }
                    }

                    if ($name == 'groups') {
                        foreach ($value_on_option as $key => $value) {
                            $valid_item = $this->validateGroup($value);
                            if (!$valid_item) {
                                unset($value_on_option[$key]);
                            }
                        }
                    }

                    if ($name == 'position') {
                        $this->setPosition($value_on_option);
                    } else {
                        $this->$name = $value_on_option;
                    }
                } else {
                    $this->$name = $config['default'];
                }
            } else {
                $this->$name = $config['default'];
            }
        }
    }

    /**
     * @param integer $position
     * @return static
     */
    public function setPosition(int $position)
    {
        $this->position = $position !== -1 && $position <= 0 ? 1 : $position;
        return $this;
    }

    /**
     * @param bool $show_text
     * @return static
     */
    public function setShowText(bool $show_text)
    {
        $this->show_text = $show_text;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function asLink()
    {
        return $this->asLink;
    }

    /**
     * @return MenuItem[]
     */
    public function getItems()
    {

        $defaultPositions = [];
        $nextPosition = 1;

        $items = $this->items;
        $itemsToOrder = [];

        foreach ($items as $item) {
            if ($item->getPosition() !== -1) {
                $defaultPositions[] = $item->getPosition();
            }
        }

        sort($defaultPositions);

        foreach ($items as $item) {

            $itemToOrder = clone $item;

            if ($itemToOrder->getPosition() === -1) {

                while (in_array($nextPosition, $defaultPositions)) {
                    $nextPosition++;
                }

                $itemToOrder->setPosition($nextPosition);
                $nextPosition++;
            }

            $itemsToOrder[] = $itemToOrder;
        }

        /**
         * @param MenuItem $a
         * @param MenuItem $b
         */
        uasort($itemsToOrder, function ($a, $b) {

            $et = 0;
            $gt = 1;
            $lt = -1;
            $result = 0;

            if ($a->getPosition() === $b->getPosition()) {
                $result = $et;
            } elseif ($a->getPosition() === -1) {
                $result = $gt;
            } elseif ($b->getPosition() === -1) {
                $result = $lt;
            } elseif ($a->getPosition() > $b->getPosition()) {
                $result = $gt;
            } else {
                $result = $lt;
            }

            return $result;
        });

        return $itemsToOrder;
    }

    /**
     * @return MenuGroup[]
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @return string|null
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        $html = $this->getHtmlElement();
        return !is_null($html) ? $html->render(false) : '';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return HtmlElement|null
     */
    public function getHtmlElement()
    {
        $group_name = $this->name;
        $group_as_link = $this->asLink;
        $group_href = !is_null($this->href) ? $this->href : '';
        $group_icon = $this->icon;
        $group_visible = $this->visible;
        $group_attributes = $this->attributes;
        $group_current = $this->isCurrent();
        $group_items = $this->getItems();
        $group_groups = $this->groups;
        $group_show_text = $this->show_text;
        $group_show_icon = $this->show_icon;
        $group_as_row = $this->as_row == true ? " as-row " : "";

        if ($group_visible) {

            if ($group_current) {
                $group_attributes['current'] = '';
            }

            if (!$group_as_link || $group_current) {
                $group_container = new HtmlElement('div', '', null, $group_attributes);
            } else {
                $group_container = new HtmlElement('a', '', null, $group_attributes);
            }

            if (!$group_show_text) {
                $group_container->setAttribute('data-content', $group_name);
            }

            if (mb_strlen(trim($group_name)) > 10 && $group_show_icon) {
                // $group_name = substr($group_name, 0, 7) . "...";
            }

            if (mb_strlen(trim($group_icon)) > 0 && $group_show_icon) {
                $group_container->setAttribute('class', 'item' . $group_as_row);

                if (strpos($group_icon, "div") !== false) {
                    $group_name_icon = "$group_icon";
                } else {
                    $group_name_icon = "<i class='icon $group_icon'></i>";
                }
                if ($group_show_text) {
                    $group_name = $group_name_icon . "<span>$group_name</span>";
                } else {
                    $group_container->setAttribute('class', 'item no-text' . $group_as_row);
                    $group_name = $group_name_icon;
                }
            } else {
                $group_container->setAttribute('class', 'item no-icon' . $group_as_row);
                $group_name = "<span>$group_name</span>";
            }

            if (!$group_as_link || $group_current) {

                // $group_title_container = new HtmlElement('div', $group_name);
                $group_container->setText($group_name);

                $last_class = $group_container->getAttribute('class');
                if ($group_current) {
                    if ($group_as_link) {
                        $group_container->setAttribute('class', 'current as-link ' . $group_as_row . $last_class);
                    } else {
                        $group_container->setAttribute('class', 'ui dropdown current ' . $group_as_row . $last_class);
                    }
                } else {
                    $group_container->setAttribute('class', 'ui dropdown ' . $group_as_row . $last_class);
                }

                if ($group_items !== false) {
                    $group_menu_container = new HtmlElement('div');
                    $group_menu_container->setAttribute('class', 'menu');
                    foreach ($group_items as $item) {
                        $group_menu_container->appendChild($item->getHtmlElement());
                    }
                    $group_container->appendChild($group_menu_container);
                }
            } else {
                // $group_container->setTag("a");
                $group_container->setText($group_name);
                $group_container->setAttribute('href', $group_href);
            }

            // if (!$group_as_link) {
            //     $group_title_container = new HtmlElement('div', $group_name);
            //     $group_title_container->setAttribute('class', 'ui dropdown item');

            //     $group_menu_container = new HtmlElement('div');
            //     $group_menu_container->setAttribute('class', 'menu');

            //     if ($group_items !== false) {
            //         foreach ($group_items as $item) {
            //             $group_menu_container->appendChild($item->getHtmlElement());
            //         }
            //     }

            //     //     foreach ($group_groups as $group) {
            //     //         $group_dropdown_container->appendChild($group->getHtmlElement());
            //     //     }
            //     // }
            //     $group_title_container->appendChild($group_menu_container);
            // }


            return $group_container;
        } else {
            return null;
        }
    }

    /**
     * @return bool
     */
    public function isCurrent()
    {
        if (is_null($this->current)) {
            $current_url = get_current_url();
            $href = $this->href;

            while (last_char($current_url) == '/') {
                $current_url = remove_last_char($current_url);
            }
            while (last_char($href) == '/') {
                $href = remove_last_char($href);
            }

            if ($href != $current_url) {
                $items = $this->getItems();
                foreach ($items as $item) {
                    if ($item->isCurrent()) {
                        $this->current = true;
                        break;
                    }
                }
                foreach ($this->groups as $group) {
                    if ($group->isCurrent()) {
                        $this->current = true;
                        break;
                    }
                }
                if ($this->current !== true) {
                    $this->current = false;
                }
            } else {
                $this->current = true;
            }
        }
        return $this->current;
    }

    /**
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * @param MenuItem $item
     * @return static
     */
    public function addItem(MenuItem $item)
    {
        $this->items[] = $item;
        return $this;
    }

    /**
     * @param MenuGroup $group
     * @return static
     */
    public function addGroup(MenuGroup $group)
    {
        $this->groups[] = $group;
        return $this;
    }

    /**
     * @param bool $visible
     * @return static
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;
        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function validateItem($value)
    {
        return $value instanceof MenuItem;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function validateGroup($value)
    {
        return $value instanceof MenuGroup;
    }
}

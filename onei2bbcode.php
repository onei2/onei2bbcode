<?php
    /*
     *   onei2bbcode - BBCode parser PHP class.
     *   Copyright (C) 2020  Ivan Ivanovic
     *
     *   This program is free software: you can redistribute it and/or modify
     *   it under the terms of the GNU General Public License as published by
     *   the Free Software Foundation, either version 3 of the License, or
     *   (at your option) any later version.
     *
     *   This program is distributed in the hope that it will be useful,
     *   but WITHOUT ANY WARRANTY; without even the implied warranty of
     *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *   GNU General Public License for more details.
     *
     *   You should have received a copy of the GNU General Public License
     *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
     */

    namespace onei2;

    class bbcode {
        function parse($text) {
            $pattern = '/\[([A-Za-z0-9-]+)(?:=([^\]]+))?\]([^\[]*)\[\/\1\]/';

            while (preg_match($pattern, $text) === 1) {
                $text = preg_replace_callback($pattern, function($matches) {
                    switch ($matches[1]) {
                        case 'b':
                        case 'i':
                        case 'u':
                        case 's':
                        case 'pre':
                        case 'code':
                        case 'tr':
                        case 'th':
                        case 'td':
                        case 'ul':
                        case 'ol':
                        case 'li':
                        case 'p':
                        case 'h1':
                        case 'h2':
                        case 'h3':
                        case 'h4':
                        case 'h5':
                        case 'h6':
                            return "<{$matches[1]}>{$matches[3]}</{$matches[1]}>";
                            break;
                        case 'table':
                            return "<div style='overflow-x:auto;'><{$matches[1]}>{$matches[3]}</{$matches[1]}></div>";
                            break;
                        case 'url':
                            if (strlen($matches[2]) > 0) {
                                return "<a target='_blank' href='{$matches[2]}'>{$matches[3]}</a>";
                            }
                            return "<a target='_blank' href='{$matches[3]}'>{$matches[3]}</a>";
                            break;
                        case 'img':
                            return "<img src='{$matches[3]}' />";
                            break;
                        case 'background-color':
                            return "<span style='background-color:{$matches[2]};'>{$matches[3]}</span>";
                            break;
                        case 'color':
                            return "<span style='color:{$matches[2]};'>{$matches[3]}</span>";
                            break;
                        case 'size':
                            return "<span style='font-size:{$matches[2]}px;'>{$matches[3]}</span>";
                            break;
                        case 'center':
                        case 'left':
                        case 'right':
                            return "<div style='text-align:{$matches[1]};'><div style='display:inline-block;'>{$matches[3]}</div></div>";
                            break;
                        case 'details':
                            return "<details><summary>{$matches[2]}</summary>{$matches[3]}</details>";
                            break;
                        case 'youtube':
                            preg_match('/v=(.+?)(?:&|$)/', $matches[3], $matches2);
                            return "<iframe allowfullscreen frameborder='0' scrolling='no' src='https://www.youtube.com/embed/{$matches2[1]}'></iframe>";
                            break;
                        case 'google-drive-image':
                            preg_match('/\/d\/([^\/]+)\//', $matches[3], $matches2);
                            return "<img src='https://drive.google.com/uc?id={$matches2[1]}' />";
                            break;
                        case 'dropbox-image':
                            $src = preg_replace('/www\.dropbox\.com/', 'dl.dropboxusercontent.com', $matches[3]);
                            return "<img src='{$src}' />";
                            break;
                    }
                }, $text);
            }

            return $text;
        }
    }
?>

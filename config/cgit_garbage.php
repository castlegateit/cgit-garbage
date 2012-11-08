<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Name: CGIT Garbage Collection
 *
 * Author:
 * Andy Reading
 * Castlegate IT
 * http://www.castlegateit.co.uk
 *
 * Copyright 2012 Castlegate IT
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Description: A simple garbage collection library
 * Released: 08/11/2012
 * Requirements: PHP5 or above and Codeigniter 2.0+
 */

/**
 * Probability of garbage collection. Integer from 0 (never) to 100 (always)
 */
$config['garbage_probability'] = 5;

/**
 * Number of seconds before garbage is ready for collection
 */
$config['garbage_lifespan'] = 60 * 60; // 1 hour

/**
 * Number of seconds before garbage is ready for collection
 */
$config['garbage_path'] = '/home/visage_technology_subsite/tmp';

/* End of file cgit_garbage.php */
/* Location: ./application/config/cgit_garbage.php */
<?php

use Brosta\App;

define('TIPOTA', 'ndhkghsopelm');
define('UNDEFINED', null);


function os_assets_path($str = '') {
	return App::_assets_path($str);
}

function os_vendor_path($str = '') {
	return App::_vendor_path($str);
}

function os_new($name = null) {
	return App::_new($name);
}

function os_use_as($name) {
	return App::_use_as($name);
}

function os_set_memory($key, $value) {
	return App::_set_memory($key, $value);
}

function os_assets_url($url = '') {
	return App::_assets_url($url);
}

function os_contains_in($haystack, $needle) {
	return App::_contains_in($haystack, $needle);
}

function os_generate_app($haystack, $needle) {
	return App::_generate_app($haystack, $needle);
}

function os_delete_file($files) {
	return App::_delete_file($files);
}

function os_disk($str = '') {
	return App::_disk($str);
}

function os_explode_dot($data) {
	return App::_explode_dot($data);
}

function os_explode_lines($data) {
	return App::_explode_lines($data);
}

function os_find($array, $something = null, $start = 0) {
	return App::_find($array, $something, $start);
}

function os_first_in($haystack, $needle) {
	return App::_first_in($haystack, $needle);
}

function os_include($file, $data = []) {
	return App::_include($file, $data);
}

function os_isset($key) {
	return App::_isset($key);
}

function os_last_in($haystack, $needle) {
	return App::_last_in($haystack, $needle);
}

function os_push($key, $value) {
	return App::_push($key, $value);
}

function os_redirect($url) {
	return App::_redirect($url);
}

function os_require($file, $position = 'require') {
	return App::_require($file, $position);
}

function os_snippet($file, $data = []) {
	return App::_snippet($file, $data);
}

function os_args_to_string_vars($array) {
	return App::_args_to_string_vars($array);
}

function os_template_path($str = '') {
	return App::_template_path($str);
}

function os_unique_id($length = 1, $str = '', $id = null) {
	return App::_unique_id($length, $str, $id);
}

function os_acceptable($value) {
	return App::_acceptable($value);
}

function os_add_class($data = null) {
	return App::_add_class($data);
}

function os_append($data) {
	return App::_append($data);
}

function os_append_after_tag($data = null) {
	return App::_append_after_tag($data);
}

function os_append_after_text($data = null) {
	return App::_append_after_text($data);
}

function os_append_before_tag($data = null) {
	return App::_append_before_tag($data);
}

function os_append_before_text($data = null) {
	return App::_append_before_text($data);
}

function os_array_replace($defaults, $replaces) {
	return App::_array_replace($defaults, $replaces);
}

function os_array_merge($defaults, $replaces) {
	return App::_array_merge($defaults, $replaces);
}

function os_array_sm($array, $count = 0, $current = 0, $first = 0) {
	return App::_array_sm($array, $count, $current, $first);
}

function os_array_unset_recursive($unset_key, $replaces, $results = [], $level = 0, $lock = 0, $stop = 0, $unlock = 0) {
	return App::_array_unset_recursive($unset_key, $replaces, $results, $level, $lock, $stop, $unlock);
}

function os_ascii_to_text($contents) {
	return App::_ascii_to_text($contents);
}

function os_assets_images_url($url = '') {
	return App::_assets_images_url($url);
}

function os_assets_img($img) {
	return App::_assets_img($img);
}

function os_attr($attr, $data = null) {
	return App::_attr($attr, $data);
}

function os_auto_route() {
	return App::_auto_route();
}

function os_body_class($classes = '') {
	return App::_body_class($classes);
}

function os_back_slash() {
	return App::_back_slash();
}

function os_build_document($data, $level = 0) {
	return App::_build_document($data, $level);
}

function os_cache($file, $contents = null) {
	return App::_cache($file, $contents);
}

function os_character($output) {
	return App::_character($output);
}

function os_checked() {
	return App::_checked();
}

function os_chkeep() {
	return App::_chkeep();
}

function os_class($data = '') {
	return App::_class($data);
}

function os_class_separator_fix(string $class) {
	return App::_class_separator_fix($class);
}

function os_collection_type_is($type) {
	return App::_collection_type_is($type);
}

function os_common_path($str = '') {
	return App::_common_path($str);
}

function os_component_exists($path) {
	return App::_component_exists($path);
}

function os_conclude() {
	return App::_conclude();
}

function os_has_contents_before() {
	return App::_has_contents_before();
}

function os_config(string $key) {
	return App::_config($key);
}

function os_set_path($path) {
	return App::_set_path($path);
}

function os_get_path() {
	return App::_get_path();
}

function os_static($key) {
	return App::_static($key);
}

function os_construct($path = null, array $signal = []) {
	return App::_construct($path, $signal);
}

function os_copy_dir($directory, $destination, $options = null) {
	return App::_copy_dir($directory, $destination, $options);
}

function os_credential() {
	return App::_credential();
}

function os_current_type_is(string $type) {
	return App::_current_type_is($type);
}

function os_set_doc_type(string $type) {
	return App::_set_doc_type($type);
}

function os_database_path($str = '') {
	return App::_database_path($str);
}

function os_default_checked($data = '') {
	return App::_default_checked($data);
}

function os_default_selected($data = '') {
	return App::_default_selected($data);
}

function os_default_text($data = '') {
	return App::_default_text($data);
}

function os_default_value($data = '') {
	return App::_default_value($data);
}

function os_delete($key) {
	return App::_delete($key);
}

function os_delete_path($directory, $preserve = false) {
	return App::_delete_path($directory, $preserve);
}

function os_document() {
	return App::_document();
}

function os_dot_to_underscore($str) {
	return App::_dot_to_underscore($str);
}

function os_dot_to_back_slash($str) {
	return App::_dot_to_back_slash($str);
}

function os_dot_to_url_s($str) {
	return App::_dot_to_url_s($str);
}

function os_echo($string) {
	return App::_echo($string);
}

function os_escape($str) {
	return App::_escape($str);
}

function os_unescape($str) {
	return App::_unescape($str);
}

function os_export(array $opts = []) {
	return App::_export($opts);
}

function os_fail($msg) {
	return App::_fail($msg);
}

function os_file_append_to_top($file, $contents) {
	return App::_file_append_to_top($file, $contents);
}

function os_finalize(int $reset = null) {
	return App::_finalize($reset);
}

function os_fix_type($value) {
	return App::_fix_type($value);
}

function os_function_exists($name) {
	return App::_function_exists($name);
}

function os_get($key, $default = null) {
	return App::_get($key, $default);
}

function os_get_alpha_from_lower_to_upper($code = null, $type = 'symbol') {
	return App::_get_alpha_from_lower_to_upper($code, $type);
}

function os_get_alpha_from_upper_to_lower($code = null, $type = 'symbol') {
	return App::_get_alpha_from_upper_to_lower($code, $type);
}

function os_get_alpha_lower($code = null, $type = 'symbol') {
	return App::_get_alpha_lower($code, $type);
}

function os_get_alpha_upper($code = null, $type = 'symbol') {
	return App::_get_alpha_upper($code, $type);
}

function os_items_length() {
	return App::_items_length();
}

function os_get_builded_text($item, $level) {
	return App::_get_builded_text($item, $level);
}

function os_class_get_reflection($instance) {
	return App::_class_get_reflection($instance);
}

function os_class_get_properties($instance) {
	return App::_class_get_properties($instance);
}

function os_class_get_methods($instance) {
	return App::_class_get_methods($instance);
}

function os_class_get_as_array($instance) {
	return App::_class_get_as_array($instance);
}

function os_get_control_chars($code = null, $type = 'symbol') {
	return App::_get_control_chars($code, $type);
}

function os_get_controller($name, $arguments = []) {
	return App::_get_controller($name, $arguments);
}

function os_get_database_names(array $op = null) {
	return App::_get_database_names($op);
}

function os_get_database_names_with_tables($keyed = 0) {
	return App::_get_database_names_with_tables($keyed);
}

function os_get_database_names_with_tables_and_data($keyed = 0) {
	return App::_get_database_names_with_tables_and_data($keyed);
}

function os_get_database_structure(string $env) {
	return App::_get_database_structure($env);
}

function os_get_database_table_columns($database, $table) {
	return App::_get_database_table_columns($database, $table);
}

function os_get_database_table_config_array(string $database, string $table, array $opts = []) {
	return App::_get_database_table_config_array($database, $table, $opts);
}

function os_get_database_table_config_string($database, $table) {
	return App::_get_database_table_config_string($database, $table);
}

function os_get_database_table_data($database, $tables) {
	return App::_get_database_table_data($database, $tables);
}

function os_get_database_table_data_all($database) {
	return App::_get_database_table_data_all($database);
}

function os_get_database_table_data_string($database, $table) {
	return App::_get_database_table_data_string($database, $table);
}

function os_get_database_tables(string $database) {
	return App::_get_database_tables($database);
}

function os_get_date_time_zone($zone) {
	return App::_get_date_time_zone($zone);
}

function os_get_dir_file($file) {
	return App::_get_dir_file($file);
}

function os_get_exported_string($list = []) {
	return App::_get_exported_string($list);
}

function os_get_items() {
	return App::_get_items();
}

function os_get_local_array($where) {
	return App::_get_local_array($where);
}

function os_get_local_form($where) {
	return App::_get_local_form($where);
}

function os_get_month($month) {
	return App::_get_month($month);
}

function os_get_name_last($str) {
	return App::_get_name_last($str);
}

function os_get_nested_items() {
	return App::_get_nested_items();
}

function os_get_non_alpha_numeric_characters() {
	return App::_get_non_alpha_numeric_characters();
}

function os_get_numbers($code = null, $type = 'symbol') {
	return App::_get_numbers($code, $type);
}

function os_get_paths($path) {
	return App::_get_paths($path);
}

function os_get_paths_only($path) {
	return App::_get_paths_only($path);
}

function os_get_ready_document(int $reset = null) {
	return App::_get_ready_document($reset);
}

function os_get_returned_array_file($file, $where) {
	return App::_get_returned_array_file($file, $where);
}

function os_get_symbol_chars($code = null, $type = 'symbol') {
	return App::_get_symbol_chars($code, $type);
}

function os_get_time_in_milliseconds() {
	return App::_get_time_in_milliseconds();
}

function os_get_type($element) {
	return App::_get_type($element);
}

function os_has_more_opened_tags() {
	return App::_has_more_opened_tags();
}

function os_has_more_closed_tags() {
	return App::_has_more_closed_tags();
}

function os_get_unknown_chars($code = null, $type = 'symbol') {
	return App::_get_unknown_chars($code, $type);
}

function os_get_body_class() {
	return App::_get_body_class();
}

function os_get_exe_options($input) {
	return App::_get_exe_options($input);
}

function os_get_include_contents($file) {
	return App::_get_include_contents($file);
}

function os_get_spaces_by_level(int $number, string $operator) {
	return App::_get_spaces_by_level($number, $operator);
}

function os_gg_alpha($output) {
	return App::_gg_alpha($output);
}

function os_gg_alpha_string($output) {
	return App::_gg_alpha_string($output);
}

function os_gg_control($output) {
	return App::_gg_control($output);
}

function os_gg_control_string($output) {
	return App::_gg_control_string($output);
}

function os_gg_number($output) {
	return App::_gg_number($output);
}

function os_gg_number_string($output) {
	return App::_gg_number_string($output);
}

function os_gg_symbol($output) {
	return App::_gg_symbol($output);
}

function os_gg_symbol_string($output) {
	return App::_gg_symbol_string($output);
}

function os_header($str) {
	return App::_header($str);
}

function os_http_build_query($data, $separator = '&', $prefix = '') {
	return App::_http_build_query($data, $separator, $prefix);
}

function os_include_exists($file) {
	return App::_include_exists($file);
}

function os_is_double($element) {
	return App::_is_double($element);
}

function os_is_file($file) {
	return App::_is_file($file);
}

function os_is_float($element) {
	return App::_is_float($element);
}

function os_is_integer($element) {
	return App::_is_integer($element);
}

function os_is_numeric($element) {
	return App::_is_numeric($element);
}

function os_is_admin() {
	return App::_is_admin();
}

function os_is_cached($file) {
	return App::_is_cached($file);
}

function os_is_closure($callback) {
	return App::_is_closure($callback);
}

function os_is_int($element) {
	return App::_is_int($element);
}

function os_is_manual($key) {
	return App::_is_manual($key);
}

function os_is_same($a, $b) {
	return App::_is_same($a, $b);
}

function os_load_components($types) {
	return App::_load_components($types);
}

function os_load_the_scripts_components() {
	return App::_load_the_scripts_components();
}

function os_log($msg) {
	return App::_log($msg);
}

function os_make_include($file, $contents = '') {
	return App::_make_include($file, $contents);
}

function os_manual($key, $value = null) {
	return App::_manual($key, $value);
}

function os_mb_str_split($str) {
	return App::_mb_str_split($str);
}

function os_mkdir($dir, $mode = 493, $recursive = true) {
	return App::_mkdir($dir, $mode, $recursive);
}

function os_mkdirs(array $array, $path = '') {
	return App::_mkdirs($array, $path);
}

function os_fmk($file, $contents = '', $lock = false) {
	return App::_fmk($file, $contents, $lock);
}

function os_mkfileforce($file, $contents = '') {
	return App::_mkfileforce($file, $contents);
}

function os_monitor() {
	return App::_monitor();
}

function os_mytime() {
	return App::_mytime();
}

function os_nested(array $data = []) {
	return App::_nested($data);
}

function os_nested_fields($array) {
	return App::_nested_fields($array);
}

function os_new_line() {
	return App::_new_line();
}

function os_new_tag() {
	return App::_new_tag();
}

function os_on($event, $callback) {
	return App::_on($event, $callback);
}

function os_parse($output) {
	return App::_parse($output);
}

function os_path_is(string $name, int $num = 0) {
	return App::_path_is($name, $num);
}

function os_pos($haystack, $needle) {
	return App::_pos($haystack, $needle);
}

function os_posted() {
	return App::_posted();
}

function os_preg_replace($regex, $expresion, $string) {
	return App::_preg_replace($regex, $expresion, $string);
}

function os_process_text_to_ascii($string, &$offset) {
	return App::_process_text_to_ascii($string, $offset);
}

function os_project($str = '') {
	return App::_project($str);
}

function os_public_path($str = '') {
	return App::_public_path($str);
}

function os_remove_spaces(string $str) {
	return App::_remove_spaces($str);
}

function os_replace($search, $replace, $subject) {
	return App::_replace($search, $replace, $subject);
}

function os_replace_spaces_with_one($str = '') {
	return App::_replace_spaces_with_one($str);
}

function os_request_all() {
	return App::_request_all();
}

function os_require_text_script($script) {
	return App::_require_text_script($script);
}

function os_reset() {
	return App::_reset();
}

function os_resources() {
	return App::_resources();
}

function os_results($output) {
	return App::_results($output);
}

function os_send() {
	return App::_send();
}

function os_set($key, $value) {
	return App::_set($key, $value);
}

function os_set_after_or_before($switch) {
	return App::_set_after_or_before($switch);
}

function os_set_start_code_space_level($level) {
	return App::_set_start_code_space_level($level);
}

function os_get_start_code_space_level() {
	return App::_get_start_code_space_level();
}

function os_cspace($exp) {
	return App::_cspace($exp);
}

function os_cspace_lock($exp) {
	return App::_cspace_lock($exp);
}

function os_set_text($text) {
	return App::_set_text($text);
}

function os_set_text_lined($set) {
	return App::_set_text_lined($set);
}

function os_set_type($type, $value) {
	return App::_set_type($type, $value);
}

function os_settings($setting) {
	return App::_settings($setting);
}

function os_slash_and_dot_to_back_slash($str) {
	return App::_slash_and_dot_to_back_slash($str);
}

function os_slash_and_dot_to_dash($str) {
	return App::_slash_and_dot_to_dash($str);
}

function os_slash_and_dot_to_space($str) {
	return App::_slash_and_dot_to_space($str);
}

function os_slash_and_dot_to_url_s($str) {
	return App::_slash_and_dot_to_url_s($str);
}

function os_slash_to_dot($str) {
	return App::_slash_to_dot($str);
}

function os_space($number) {
	return App::_space($number);
}

function os_space_like_tab($number) {
	return App::_space_like_tab($number);
}

function os_space_to_dash($str) {
	return App::_space_to_dash($str);
}

function os_storage($str = '') {
	return App::_storage($str);
}

function os_storage_copy($source, $destination) {
	return App::_storage_copy($source, $destination);
}

function os_str_trans($one, $two) {
	return App::_str_trans($one, $two);
}

function os_string_length($str) {
	return App::_string_length($str);
}

function os_style($data) {
	return App::_style($data);
}

function os_style_to_file($style, $class) {
	return App::_style_to_file($style, $class);
}

function os_syntax_error($error, $line = 0) {
	return App::_syntax_error($error, $line);
}

function os_system_all() {
	return App::_system_all();
}

function os_tab_space($number) {
	return App::_tab_space($number);
}

function os_table($table) {
	return App::_table($table);
}

function os_tag($tag = null) {
	return App::_tag($tag);
}

function os_text($text = null, $line = 0) {
	return App::_text($text, $line);
}

function os_text_to_ascii($text) {
	return App::_text_to_ascii($text);
}

function os_title(string $string) {
	return App::_title($string);
}

function os_to_base($key) {
	return App::_to_base($key);
}

function os_to_back_slash($str) {
	return App::_to_back_slash($str);
}

function os_to_url_s($str = '') {
	return App::_to_url_s($str);
}

function os_ucfirst($str) {
	return App::_ucfirst($str);
}

function os_underscore_to_upercase($name) {
	return App::_underscore_to_upercase($name);
}

function os_update_memory($data) {
	return App::_update_memory($data);
}

function os_upper_to_underscore($string) {
	return App::_upper_to_underscore($string);
}

function os_url($extend = '', $args = [], $replace = []) {
	return App::_url($extend, $args, $replace);
}

function os_url_s() {
	return App::_url_s();
}

function os_views_path($str = '') {
	return App::_views_path($str);
}

function os_upper($str) {
	return App::_upper($str);
}

function os_fwrite($file, $contents, $lock = false) {
	return App::_fwrite($file, $contents, $lock);
}

function os_key_in_array($key, $array) {
	return App::_key_in_array($key, $array);
}

function os_is_array($element) {
	return App::_is_array($element);
}

function os_substr(string $string, $start = 0, $length = null) {
	return App::_substr($string, $start, $length);
}

function os_is_dir($str) {
	return App::_is_dir($str);
}

function os_is_empty($something) {
	return App::_is_empty($something);
}

function os_get_file_contents($file, $lock = false) {
	return App::_get_file_contents($file, $lock);
}

function os_exit() {
	return App::_exit();
}

function os_unlink($file) {
	return App::_unlink($file);
}

function os_count($str) {
	return App::_count($str);
}

function os_basename($path) {
	return App::_basename($path);
}

function os_array_zero($array) {
	return App::_array_zero($array);
}

function os_explode($separator, $string) {
	return App::_explode($separator, $string);
}

function os_implode($separator, $array) {
	return App::_implode($separator, $array);
}

function os_in_array($key, $element) {
	return App::_in_array($key, $element);
}

function os_file_append($file, $contents) {
	return App::_file_append($file, $contents);
}

function os_decode($data) {
	return App::_decode($data);
}

function os_copy_file($path, $target) {
	return App::_copy_file($path, $target);
}

function os_make_dir_force($file, $mode = 493, $recursive = true) {
	return App::_make_dir_force($file, $mode, $recursive);
}

function os_is_null($element) {
	return App::_is_null($element);
}

function os_is_object($element) {
	return App::_is_object($element);
}

function os_is_string($element) {
	return App::_is_string($element);
}

function os_length($str) {
	return App::_length($str);
}

function os_trim($str, $character_mask = null) {
	return App::_trim($str, $character_mask);
}

function os_rtrim($source, $sym = null) {
	return App::_rtrim($source, $sym);
}

function os_print($str) {
	return App::_print($str);
}

function os_ltrim($source, $sym = null) {
	return App::_ltrim($source, $sym);
}

function os_lower($str) {
	return App::_lower($str);
}

function os_require_local($file, $data = []) {
	return App::_require_local($file, $data);
}

function os_file_exists($file) {
	return App::_file_exists($file);
}

function os_file_extention($file) {
	return App::_file_extention($file);
}

function os_fix_prefix_app(string $str) {
	return App::_fix_prefix_app($str);
}

function os_growth() {
	return App::_growth();
}

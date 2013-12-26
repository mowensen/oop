/*
 * CodePress regular expressions for PHP syntax highlighting
 */

// PHP
Language.syntax = [
	{ input : /(&lt;(.*?)&gt;)/g, output : '<b>$1</b>' }, // all tags
	{ input : /(&lt;cms[^!\?]*?&gt;)/g, output : '<samp>$1</samp>' }, // cms tags
	{ input : /(&lt;\/cms[^!\?]*?&gt;)/g, output : '<samp>$1</samp>' }, // cms tags
	{ input : /(¤cms(.*?)¤)/g, output : '<samp>$1</samp>' }, // cms vars
	{ input : /(#rtm(.*?)#)/g, output : '<samp>$1</samp>' }, // cms vars
	{ input : /(&lt;style.*?&gt;)(.*?)(&lt;\/style&gt;)/g, output : '<em>$1</em><em>$2</em><em>$3</em>' }, // style tags
	{ input : /(&lt;script.*?&gt;)(.*?)(&lt;\/script&gt;)/g, output : '<ins>$1</ins><ins>$2</ins><ins>$3</ins>' }, // script tags
	{ input : /\"(.*?)(\"|<br>|<\/P>)/g, output : '<s>"$1$2</s>' }, // strings double quote
	{ input : /\'(.*?)(\'|<br>|<\/P>)/g, output : '<s>\'$1$2</s>'}, // strings single quote
	{ input : /(&lt;\?)/g, output : '<strong>$1' }, // <?.*
	{ input : /(\?&gt;)/g, output : '$1</strong>' }, // .*?>
	{ input : /(&lt;\?php|&lt;\?=|&lt;\?|\?&gt;)/g, output : '<strong>$1</strong>' }, // php tags
	{ input : /(\$[\w\.]*)/g, output : '<a>$1</a>' }, // vars
 // reserved words
 	{ input : /\b(false|true|and|or|xor|__FILE__|exception|__LINE__|array|as|break|case|class|const|continue|declare|default|die|do|echo|else|elseif|empty|enddeclare|endfor|endforeach|endif|endswitch|endwhile|eval|exit|extends|for|foreach|function|global|if|include|include_once|isset|list|new|print|require|require_once|return|static|switch|unset|use|while|__FUNCTION__|__CLASS__|__METHOD__|final|php_user_filter|interface|implements|extends|public|private|protected|abstract|clone|try|catch|throw|this)\b/g, output : '<cite>$1</cite>' },
// string functions
	{ input : /(addcslashes|addslashes|bin2hex|chop|chr|chunk_split|convert_cyr_string|count_chars|crc32|crypt|echo|explode|fprintf|get_html_translation_table|hebrev|hebrevc|html_entity_decode|htmlentities|htmlspecialchars|implode|join|levenshtein|localeconv|ltrim|md5_file|md5|metaphone|money_format|nl_langinfo|nl2br|number_format|ord|parse_str|print|printf|quoted_printable_decode|quotemeta|rtrim|setlocale|sha1_file|sha1|similar_text|soundex|sprintf|sscanf|str_ireplace|str_pad|str_repeat|str_replace|str_rot13|str_shuffle|str_split|str_word_count|strcasecmp|strchr|strcmp|strcoll|strcspn|strip_tags|stripcslashes|stripos|stripslashes|stristr|strlen|strnatcasecmp|strnatcmp|strncasecmp|strncmp|strpos|strrchr|strrev|strripos|strrpos|strspn|strstr|strtok|strtolower|strtoupper|strtr|substr_compare|substr_count|substr_replace|substr|trim|ucfirst|ucwords|vprintf|vsprintf|wordwrap)/gi, output: '<cite>$1</cite>'},
// options & information fnctions
	{ input : /(assert_options|assert|dl|extension_loaded|get_cfg_var|get_current_user|get_defined_constants|get_extension_funcs|get_include_path|get_included_files|get_loaded_extensions|get_magic_quotes_gpc|get_magic_quotes_runtime|get_required_files|getenv|getlastmod|getmygid|getmyinode|getmypid|getmyuid|getopt|getrusage|ini_alter|ini_get_all|ini_get|ini_restore|ini_set|main|memory_get_usage|php_ini_scanned_files|php_logo_guid|php_sapi_name|php_uname|phpcredits|phpinfo|phpversion|putenv|restore_include_path|set_include_path|set_magic_quotes_runtime|set_time_limit|version_compare|zend_logo_guid|zend_version)/g, output : '<cite>$1</cite>'},
// output Control Functions
	{ input : /(flush|ob_clean|ob_end_clean|ob_end_flush|ob_flush|ob_get_clean|ob_get_contents|ob_get_flush|ob_get_length|ob_get_level|ob_get_status|ob_gzhandler|ob_implicit_flush|ob_list_handlers|ob_start|output_add_rewrite_var|output_reset_rewrite_vars)/g, output : '<cite>$1</cite>'},
//mysql functions
	{ input : /(mysql_affected_rows|mysql_change_user|mysql_client_encoding|mysql_close|mysql_connect|mysql_create_db|mysql_data_seek|mysql_db_name|mysql_db_query|mysql_drop_db|mysql_errno|mysql_error|mysql_escape_string|mysql_fetch_array|mysql_fetch_assoc|mysql_fetch_field|mysql_fetch_lengths|mysql_fetch_object|mysql_fetch_row|mysql_field_flags|mysql_field_len|mysql_field_name|mysql_field_seek|mysql_field_table|mysql_field_type|mysql_free_result|mysql_get_client_info|mysql_get_host_info|mysql_get_proto_info|mysql_get_server_info|mysql_info|mysql_insert_id|mysql_list_dbs|mysql_list_fields|mysql_list_processes|mysql_list_tables|mysql_num_fields|mysql_num_rows|mysql_pconnect|mysql_ping|mysql_query|mysql_real_escape_string|mysql_result|mysql_select_db|mysql_stat|mysql_tablename|mysql_thread_id|mysql_unbuffered_query)/g, output : '<cite>$1</cite>'},
//HTTP functions
	{ input : /(header|headers_list|headers_sent|setcookie)/g, output : '<cite>$1</cite>'},
//ftp functions
	{ input : /(ftp_alloc|ftp_cdup|ftp_chdir|ftp_chmod|ftp_close|ftp_connect|ftp_delete|ftp_exec|ftp_fget|ftp_fput|ftp_get_option|ftp_get|ftp_login|ftp_mdtm|ftp_mkdir|ftp_nb_continue|ftp_nb_fget|ftp_nb_fput|ftp_nb_get|ftp_nb_put|ftp_nlist|ftp_pasv|ftp_put|ftp_pwd|ftp_quit|ftp_raw|ftp_rawlist|ftp_rename|ftp_rmdir|ftp_set_option|ftp_site|ftp_size|ftp_ssl_connect|ftp_systype)/g, output : '<cite>$1</cite>'},
//file functions
	{ input : /(chgrp|chmod|chown|clearstatcache|copy|delete|dirname|disk_free_space|disk_total_space|diskfreespace|fclose|feof|fflush|fgetc|fgetcsv|fgets|fgetss|file_exists|file_get_contents|file_put_contents|file|fileatime|filectime|filegroup|fileinode|filemtime|fileowner|fileperms|filesize|filetype|flock|fnmatch|fopen|fpassthru|fputs|fread|fscanf|fseek|fstat|ftell|ftruncate|fwrite|glob|is_dir|is_executable|is_file|is_link|is_readable|is_uploaded_file|is_writable|is_writeable|link|linkinfo|lstat|mkdir|move_uploaded_file|parse_ini_file|pathinfo|pclose|popen|readfile|readlink|realpath|rename|rewind|rmdir|set_file_buffer|stat|symlink|tempnam|tmpfile|touch|umask|unlink)/g, output : '<cite>$1</cite>'},
//directory functions
	{ input : /(chdir|chroot|dir|closedir|getcwd|opendir|readdir|rewinddir|scandir)/g, output : '<cite>$1</cite>'},
//date time functions
	{ input : /(checkdate|date|getdate|gettimeofday|gmdate|gmmktime|gmstrftime|localtime|microtime|mktime|strftime|strtotime|time)/g, output : '<cite>$1</cite>'},
//array functions
	{ input : /(array_change_key_case|array_chunk|array_combine|array_count_values|array_diff_assoc|array_diff_uassoc|array_diff|array_fill|array_filter|array_flip|array_intersect_assoc|array_intersect|array_key_exists|array_keys|array_map|array_merge_recursive|array_merge|array_multisort|array_pad|array_pop|array_push|array_rand|array_reduce|array_reverse|array_search|array_shift|array_slice|array_splice|array_sum|array_udiff_assoc|array_udiff_uassoc|array_udiff|array_unique|array_unshift|array_values|array_walk|array|arsort|asort|compact|count|current|each|end|extract|in_array|key|krsort|ksort|list|natcasesort|natsort|next|pos|prev|range|reset|rsort|shuffle|sizeof|sort|uasort|uksort|usort)/g, output : '<cite>$1</cite>'},
//Program Execution functions
	{ input : /(escapeshellarg|escapeshellcmd|exec|passthru|proc_close|proc_get_status|proc_nice|proc_open|proc_terminate|shell_exec|system)/g, output : '<cite>$1</cite>'},
//delimiters
	{ input : /(\@|\(|\))/g, output : '<cite>$1</cite>'},
	{ input : /(\[|\])/g, output : '<a>$1</a>'},
	{ input : /(\{|\})/g, output : '<strong>$1</strong>'},
	
	{ input : /([^:])\/\/(.*?)(<br|<\/P)/g, output : '$1<i>//$2</i>$3' }, // php comments //
	{ input : /([^:])#(.*?)(<br|<\/P)/g, output : '$1<i>#$2</i>$3' }, // php comments #
	{ input : /\/\*(.*?)\*\//g, output : '<i>/*$1*/</i>' }, // php comments
	{ input : /(&lt;!--.*?--&gt.)/g, output : '<big>$1</big>' } // html comments
]

Language.snippets = [
	{ input : 'if', output : 'if($0){\n\t\n}' },
	{ input : 'ifelse', output : 'if($0){\n\t\n}\nelse{\n\t\n}' },
	{ input : 'else', output : '}\nelse {\n\t' },
	{ input : 'elseif', output : '}\nelseif($0) {\n\t' },
	{ input : 'do', output : 'do{\n\t$0\n}\nwhile();' },
	{ input : 'inc', output : 'include_once("$0");' },
	{ input : 'fun', output : 'function $0(){\n\t\n}' },	
	{ input : 'func', output : 'function $0(){\n\t\n}' },	
	{ input : 'while', output : 'while($0){\n\t\n}' },
	{ input : 'for', output : 'for($0,,){\n\t\n}' },
	{ input : 'fore', output : 'foreach($0 as ){\n\t\n}' },
	{ input : 'foreach', output : 'foreach($0 as ){\n\t\n}' },
	{ input : 'echo', output : 'echo \'$0\';' },
	{ input : 'switch', output : 'switch($0) {\n\tcase "": break;\n\tdefault: ;\n}' },
	{ input : 'case', output : 'case "$0" : break;' },
	{ input : 'ret0', output : 'return false;' },
	{ input : 'retf', output : 'return false;' },
	{ input : 'ret1', output : 'return true;' },
	{ input : 'rett', output : 'return true;' },
	{ input : 'ret', output : 'return $0;' },
	{ input : 'def', output : 'define(\'$0\',\'\');' },
	{ input : '<?', output : 'php\n$0\n?>' }
]

Language.complete = [
	{ input : '\'', output : '\'$0\'' },
	{ input : '"', output : '"$0"' },
	{ input : '(', output : '$0\)' },
	{ input : '[', output : '\[$0\]' },
	{ input : '{', output : '{\n\t$0\n}' }		
]

Language.shortcuts = [
	{ input : '[space]', output : '&nbsp;' },
	{ input : '[enter]', output : '<br />' } ,
	{ input : '[j]', output : 'testing' },
	{ input : '[7]', output : '&amp;' }
]

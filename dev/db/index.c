
/*
  +------------------------------------------------------------------------+
  | Phalcon Framework                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2012 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif

#include "php.h"
#include "php_phalcon.h"
#include "phalcon.h"

#include "Zend/zend_operators.h"
#include "Zend/zend_exceptions.h"
#include "Zend/zend_interfaces.h"

#include "kernel/main.h"
#include "kernel/memory.h"

#include "kernel/object.h"
#include "kernel/array.h"
#include "kernel/fcall.h"
/**
 * Phalcon_Db_Index
 *
 * Allows to define indexes to be used on tables. Indexes are a common way
 * to enhance database performance. An index allows the database server to find
 * and retrieve specific rows much faster than it could do without an index.
 *
 * 
 *
 */

/**
 * Phalcon_Db_Index constructor
 *
 * @param string $indexName
 * @param array $columns
 */
PHP_METHOD(Phalcon_Db_Index, __construct){

	zval *index_name = NULL, *columns = NULL;

	PHALCON_MM_GROW();
	
	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zz", &index_name, &columns) == FAILURE) {
		PHALCON_MM_RESTORE();
		RETURN_NULL();
	}

	phalcon_update_property_zval(this_ptr, SL("_indexName"), index_name TSRMLS_CC);
	phalcon_update_property_zval(this_ptr, SL("_columns"), columns TSRMLS_CC);
	
	PHALCON_MM_RESTORE();
}

/**
 * Gets the index name
 *
 * @return string
 */
PHP_METHOD(Phalcon_Db_Index, getName){

	zval *t0 = NULL;

	PHALCON_MM_GROW();
	PHALCON_ALLOC_ZVAL_MM(t0);
	phalcon_read_property(&t0, this_ptr, SL("_indexName"), PHALCON_NOISY TSRMLS_CC);
	
	RETURN_CHECK_CTOR(t0);
}

/**
 * Gets the columns that comprends the index
 *
 * @return array
 */
PHP_METHOD(Phalcon_Db_Index, getColumns){

	zval *t0 = NULL;

	PHALCON_MM_GROW();
	PHALCON_ALLOC_ZVAL_MM(t0);
	phalcon_read_property(&t0, this_ptr, SL("_columns"), PHALCON_NOISY TSRMLS_CC);
	
	RETURN_CHECK_CTOR(t0);
}

/**
 * Restore a Phalcon_Db_Index object from export
 *
 * @param array $data
 * @return Phalcon_Db_Index
 */
PHP_METHOD(Phalcon_Db_Index, __set_state){

	zval *data = NULL;
	zval *i0 = NULL;
	zval *r0 = NULL, *r1 = NULL;
	int eval_int;

	PHALCON_MM_GROW();
	
	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &data) == FAILURE) {
		PHALCON_MM_RESTORE();
		RETURN_NULL();
	}

	eval_int = phalcon_array_isset_string(data, SL("_indexName")+1);
	if (!eval_int) {
		PHALCON_THROW_EXCEPTION_STR(phalcon_db_exception_ce, "_indexName parameter is required");
		return;
	}
	eval_int = phalcon_array_isset_string(data, SL("_columns")+1);
	if (!eval_int) {
		PHALCON_THROW_EXCEPTION_STR(phalcon_db_exception_ce, "_columns parameter is required");
		return;
	}
	
	PHALCON_ALLOC_ZVAL_MM(i0);
	object_init_ex(i0, phalcon_db_index_ce);
	
	PHALCON_ALLOC_ZVAL_MM(r0);
	phalcon_array_fetch_string(&r0, data, SL("_indexName"), PHALCON_NOISY TSRMLS_CC);
	
	PHALCON_ALLOC_ZVAL_MM(r1);
	phalcon_array_fetch_string(&r1, data, SL("_columns"), PHALCON_NOISY TSRMLS_CC);
	PHALCON_CALL_METHOD_PARAMS_2_NORETURN(i0, "__construct", r0, r1, PHALCON_CHECK);
	
	RETURN_CTOR(i0);
}


<?php

/**
 * Названия колонок в таблице `users`
 */
const USER_PRIMARY_KEY	= 'id';					// идентификатор
const USER_LOGIN		 		= 'login';			// логин
const USER_PASSWORD		 	= 'password';		// пароль
/**
 * Названия колонок в таблице `articles`
 */
const ARTICLE_PRIMARY_KEY 	= 'id';				// идентификатор
const ARTICLE_TITLE 				= 'name';			// название
const ARTICLE_CONTENT 			= 'content';	// содержимое
const ARTICLE_DATE 					= 'dt';				// дата добавления
const ARTICLE_USER_ID 			= 'id_user';	// идентификатор автора
/**
 * Названия колонок в таблице `session`
 */
const SESS_PRIMARY_KEY	= 'id_session';		// идентификатор sess = идентификатор user
const SESS_ONLINE		 		= 'online';				// активность (bool)
const SESS_RENEWAL		 	= 'renewal';			// обновление (date)
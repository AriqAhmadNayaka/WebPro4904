<?php

class AppConfig
{
    public const DB_HOST = 'localhost';
    public const DB_USER = 'root';
    public const DB_PASS = '';
    public const DB_NAME = 'db_webprodata';

    public const LOGIN_USERNAME = 'admin';
    public const LOGIN_PASSWORD = '12345';

    public const UPLOAD_DIR = __DIR__ . '/../upload/';
    public const UPLOAD_PATH = 'upload/';

    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
}

Operation manual
=================

# Configuration

You need to set some environment variables in order to configure the Email daemon, such as in the following example:

* `IDOS_VERSION`: indicates the version of idOS API to use (default: '1.0');
* `IDOS_DEBUG`: indicates whether to enable debugging (default: false);
* `IDOS_LOG_FILE`: is the path for the generated log file (default: 'log/cra.log');
* `IDOS_GEARMAN_SERVERS`: a list of gearman servers that the daemon will register on (default: 'localhost:4730');
* `IDOS_EMAIL_HOST`: the host of the email SMTP server (default: 'smtp.gmail.com');
* `IDOS_EMAIL_PORT`: the port of the email SMTP server (default: 587);
* `IDOS_EMAIL_USER`: the username used to authenticate within the SMTP server;
* `IDOS_EMAIL_PASS`: the password used to authenticate within the SMTP server;
* `IDOS_EMAIL_ENCRYPTION`: the SMTP server encryption (default: 'tls').

You may also set these variables using a `.env` file in the project root.

# Running

In order to start the Email daemon you should run in the terminal:

```
./email-cli.php email:daemon [-d] [-l path/to/log/file] functionName serverList
```

* `functionName`: gearman function name
* `serverList`: a list of the gearman servers
* `-d`: enable debug mode
* `-l`: the path for the log file

Example:

```
./email-cli.php email:daemon -d -l log/email.log email localhost
```

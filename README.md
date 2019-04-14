# httpsec (beta)

Test a site's HTTP headers for possible security issues

**Basic usage**
```shell
$ httpsec https://www.target.com
```

**Advances usage**

If you're trying to test an URL that requires authentication, a POST request, or anything
of the like, you can use `curl` and pipe the result to `httpsec`
```shell
$ curl https://yahoo.com/ --head -sS | httpsec
```

**Screenshot**

![screenshot](resources/screenshots/screenshot.png)

**Installation**

```shell
$ curl https://github.com/nicoSWD/httpsec/releases/download/v0.1/httpsec.phar -L -o /usr/local/bin/httpsec
$ chmod u+x /usr/local/bin/httpsec
```

**Build from source**

**Build**
```shell
$ make
```

**Test**
```shell
$ make test
```

**Install**
```shell
$ make install
```

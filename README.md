# httpsec (beta)

Test a site's HTTP headers for possible security issues

**Basic usage**
```shell
$ httpsec https://www.target.com
```

**Advances usage**

If you're trying to test a site that requires authentication, a POST request, or anything
of the like, you can use `curl` and pipe the result to `httpsec`
```shell
$ curl https://yahoo.com/ --head | httpsec
```

**Screenshot**

![screenshot](resources/screenshots/screenshot.png)

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

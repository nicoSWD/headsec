# headsec (beta)
[![Build Status](https://travis-ci.org/nicoSWD/headsec.svg?branch=master)](https://travis-ci.org/nicoSWD/headsec)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/nicoSWD/headsec/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/nicoSWD/headsec/?branch=master)

Test a site's HTTP headers for possible security issues

**Basic usage**
```shell
headsec https://www.target.com
```

**Advanced usage**

If you're trying to test an URL that requires authentication, a POST request, or anything
of the like, you can use `curl` and pipe the result to `headsec`
```shell
curl https://yahoo.com/ --head -sS | headsec
```

**Screenshot**

![screenshot](screenshots/screenshot.gif)

**Installation**

```shell
curl https://raw.githubusercontent.com/nicoSWD/headsec/master/install.sh | sh
```

**Build from source**

[composer](https://getcomposer.org) needs to be installed globally in order to build this

**Build**
```shell
make
```

**Test**
```shell
make test
```

**Install**
```shell
make install
```

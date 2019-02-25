---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://dangwen.com/docs/collection.json)
<!-- END_INFO -->

#general
<!-- START_0f15af4a72ec033d66ef9a320727b267 -->
## /

> Example request:

```bash
curl "http://dangwen.com//" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://dangwen.com//",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET /`

`HEAD /`


<!-- END_0f15af4a72ec033d66ef9a320727b267 -->

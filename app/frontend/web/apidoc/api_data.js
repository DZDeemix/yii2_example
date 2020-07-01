define({ "api": [
  {
    "type": "GET",
    "url": "/api/settings",
    "title": "Настройки проекта",
    "name": "Settings",
    "group": "Api",
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "{\n  \"result\": \"OK\",\n  \"settings\": {\n    \"project_title\": \"ПРОМО\",\n    \"logoUrl\": \"https://api-showcase.msforyou.ru/data/api/1_img_logo.png\",\n    \"faviconUrl\": \"https://api-showcase.msforyou.ru/data/api/1_img_favicon.ico\",\n    \"bgUrl\": \"https://api-showcase.msforyou.ru/data/api/1_img_bg.jpg\",\n    \"color_primary\": \"#3e4c66\",\n    \"color_secondary\": \"#FFF\",\n    \"taxes\": true,\n    \"extranet\": false,\n    \"eps\": true,\n    \"payments\": true,\n    \"shop\": true,\n    \"courses\": true,\n    \"sales\": true,\n    \"news\": true,\n    \"instructions\": true,\n    \"webpush\": false,\n    \"rsbCards\": true,\n    \"module_tickets\": true,\n    \"module_notifications\": true,\n    \"module_banners\": true,\n    \"module_survey\": true,\n    \"profile_pers\": false,\n    \"registration\": \"free_by_phone\",\n    \"registration_blocked\": false,\n    \"register_rules\": true,\n    \"register_pers\": true,\n    \"register_birthday\": true,\n    \"register_gender\": true,\n    \"register_specialty\": true,\n    \"register_role\": false,\n    \"register_city\": true,\n    \"register_dealer\": false,\n    \"layout\": \"menu_top\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-api/frontend/controllers/SettingsController.php",
    "groupTitle": "Api"
  },
  {
    "type": "post",
    "url": "/api/token/check-email",
    "title": "Проверить Email-код",
    "description": "<p>Проверка E-mail кода</p>",
    "name": "TokenCheckEmail",
    "group": "Api",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>E-mail адрес</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип кода (необязательный параметр)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>Код для проверки</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"email\": \"7binary@bk.ru\",\n  \"type\": \"email_noprofile\",\n  \"code\": \"81824\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "{\n  \"result\": \"OK\",\n  \"token\": \"a7bbbc29091591b3cc5a4e67eded7e5a1751aa8464af07dab7b31afbc22969f0\",\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-api/frontend/controllers/TokenController.php",
    "groupTitle": "Api"
  },
  {
    "type": "post",
    "url": "/api/token/check-sms",
    "title": "Проверить СМС-код",
    "description": "<p>Проверияет СМС код и возвращает token</p>",
    "name": "TokenCheckSms",
    "group": "Api",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>Мобильный телефон</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип кода (необязательный параметр)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>СМС код для проверки</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"phone\": \"+7 (915) 191-3583\",\n  \"type\": \"sms_profile_unregistered\",\n  \"code\": \"03377\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result",
            "description": "<p>&quot;OK&quot; при успешном запросе</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Токен</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "{\n  \"result\": \"OK\",\n  \"token\": \"108867c27c909d3110ebeb09993cad9a7450200dda953ae6d5b6ef028066d061\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-api/frontend/controllers/TokenController.php",
    "groupTitle": "Api"
  },
  {
    "type": "post",
    "url": "/api/token/get-email",
    "title": "Получить Email-код",
    "description": "<p>Получение E-mail кода</p>",
    "name": "TokenGetEmail",
    "group": "Api",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>E-mail адрес</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип кода (необязательный параметр), доступые значения:<br/> &quot;email&quot; - E-mail<br/> &quot;email_profile&quot; - E-mail по участнику<br/> &quot;email_profile_unregistered&quot; - E-mail по участнику еще не зарегистрированному<br/> &quot;email_noprofile&quot; - E-mail если нет участника<br/></p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"email\": \"7binary@bk.ru\",\n  \"type\": \"email_noprofile\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "{\n  \"result\": \"OK\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-api/frontend/controllers/TokenController.php",
    "groupTitle": "Api"
  },
  {
    "type": "post",
    "url": "/api/token/get-sms",
    "title": "Получить СМС-код",
    "description": "<p>Получения СМС кода проверки телефона</p>",
    "name": "TokenGetSms",
    "group": "Api",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>Мобильный телефон</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип кода (необязательный параметр), доступые значения:<br/> &quot;sms&quot; - СМС<br/> &quot;sms_profile&quot; - СМС по участнику<br/> &quot;sms_profile_unregistered&quot; - СМС по участнику еще не зарегистрированному<br/> &quot;sms_noprofile&quot; - СМС если нет участника<br/></p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"phone\": \"+7 (915) 191-3583\",\n  \"type\": \"sms_profile_unregistered\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "{\n  \"result\": \"OK\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-api/frontend/controllers/TokenController.php",
    "groupTitle": "Api"
  },
  {
    "type": "post",
    "url": "/banners/api/banners/by-group",
    "title": "Получить список баннеров по группе",
    "name": "BannersByGroup",
    "group": "Banners",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "group",
            "description": "<p>Название группы</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"group\": \"guest-index\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "banners",
            "description": "<p>Данные по участнику</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.position",
            "description": "<p>Позиция баннера в группе</p>"
          },
          {
            "group": "Success 200",
            "type": "Url",
            "optional": false,
            "field": "banners.banner_url",
            "description": "<p>Ссылка на картинку баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Link",
            "optional": false,
            "field": "banners.link",
            "description": "<p>Ссылка баннера, переход туда по нажатию в новое окно</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.width",
            "description": "<p>Высота баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.height",
            "description": "<p>Ширина баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "banners.group",
            "description": "<p>Группа баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.group.width",
            "description": "<p>Высота группы баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.group.height",
            "description": "<p>Ширина группы баннера</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"banners\": [\n    {\n      \"id\": 2,\n      \"width\": null,\n      \"height\": null,\n      \"position\": 1,\n      \"banner_url\": \"http://unitile.f/data/banners/2_banner_5b34e80d612a9.jpg\",\n      \"link\": \"\",\n      \"mobile_banner_url\": null,\n      \"mobile_width\": null,\n      \"mobile_height\": null,\n      \"mobile_on\": 1,\n      \"group\": {\n        \"id\": 1,\n        \"name\": \"guest-index\",\n        \"title\": \"На главной гостевой\",\n        \"width\": 1212,\n        \"height\": 591\n      }\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-banners/frontend/controllers/api/BannersController.php",
    "groupTitle": "Banners"
  },
  {
    "type": "post",
    "url": "/banners/api/banners/groups",
    "title": "Получение групп баннеров",
    "name": "BannersGroups",
    "group": "Banners",
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-banners/frontend/controllers/api/BannersController.php",
    "groupTitle": "Banners"
  },
  {
    "type": "post",
    "url": "/banners/api/banners/list",
    "title": "Получение списка баннеров",
    "name": "BannersList",
    "group": "Banners",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "banners",
            "description": "<p>Данные по участнику</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.position",
            "description": "<p>Позиция баннера в группе</p>"
          },
          {
            "group": "Success 200",
            "type": "Url",
            "optional": false,
            "field": "banners.banner_url",
            "description": "<p>Ссылка на картинку баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Link",
            "optional": false,
            "field": "banners.link",
            "description": "<p>Ссылка баннера, переход туда по нажатию в новое окно</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.width",
            "description": "<p>Высота баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.height",
            "description": "<p>Ширина баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "banners.group",
            "description": "<p>Группа баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.group.width",
            "description": "<p>Высота группы баннера</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "banners.group.height",
            "description": "<p>Ширина группы баннера</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"banners\": [\n    {\n      \"id\": 2,\n      \"width\": null,\n      \"height\": null,\n      \"position\": 1,\n      \"banner_url\": \"http://unitile.f/data/banners/2_banner_5b34e80d612a9.jpg\",\n      \"link\": \"\",\n      \"mobile_banner_url\": null,\n      \"mobile_width\": null,\n      \"mobile_height\": null,\n      \"mobile_on\": 1,\n      \"group\": {\n        \"id\": 1,\n        \"name\": \"guest-index\",\n        \"title\": \"На главной гостевой\",\n        \"width\": 1212,\n        \"height\": 591\n      }\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-banners/frontend/controllers/api/BannersController.php",
    "groupTitle": "Banners"
  },
  {
    "type": "post",
    "url": "/bonuses/api/bonuses",
    "title": "Список бонусов",
    "name": "Index",
    "group": "Bonuses",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n    \"profile_id\": 12\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n    \"result\": \"OK\",\n    \"bonuses\": [\n        {\n            \"external_id\": \"100\",\n            \"date\": \"19.07.2019\",\n            \"paid\": \"05.08.2019\",\n            \"bonuses\": 3000,\n            \"sales_summary\": 123000\n        },\n        {\n            \"external_id\": \"101\",\n            \"date\": \"19.07.2019\",\n            \"paid\": \"05.08.2019\",\n            \"bonuses\": 1500,\n            \"sales_summary\": 56000\n        }\n    ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/modules/bonuses/frontend/controllers/api/BonusesController.php",
    "groupTitle": "Bonuses"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/cards/check",
    "title": "2. Данные о сертификате",
    "name": "Card_check",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_order_id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"ms_order_id\": 19,\n  \"ms_card_id\": 24\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "card",
            "description": "<p>Данные о сертификате</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.external_ordered_card_id",
            "description": "<p>Внешний ID заказанного сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.card",
            "description": "<p>Тип карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.card_title",
            "description": "<p>Заголов</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.card_name",
            "description": "<p>Название</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.id1c",
            "description": "<p>Идентификатор в 1С</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.nominal",
            "description": "<p>Номинал карты</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.qty",
            "description": "<p>Количество карт</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.status",
            "description": "<p>Статус сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.status_label",
            "description": "<p>Расшифровка статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.image",
            "description": "<p>Ссылка на изображение сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "card.cards",
            "description": "<p>Состав сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.cards.ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "card.cards.card_data",
            "description": "<p>Данные карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.cards.card_data.number",
            "description": "<p>Номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.cards.card_data.pin",
            "description": "<p>ПИН</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.cards.card_data.expireOn",
            "description": "<p>Срок действия</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.cards.created_at",
            "description": "<p>Время создания</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.cards.nominal",
            "description": "<p>Номинал</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.cards.type",
            "description": "<p>Тип сертификата</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"card\": {\n    \"ms_card_id\": 24,\n    \"external_ordered_card_id\": null,\n    \"card\": \"Sportmaster\",\n    \"card_title\": \"Спортмастер\",\n    \"card_name\": \"Электронный подарочный сертификат Спортмастер\",\n    \"id1c\": \"00000008012\",\n    \"nominal\": 5000,\n    \"qty\": 1,\n    \"status\": \"ready\",\n    \"status_label\": \"Полностью выдан\",\n    \"image\": \"http://dabpums.f/media/cards/8.jpg\",\n    \"cards\": [\n      {\n        \"ms_card_id\": 27,\n        \"card_data\": {\n          \"number\": \"9300030796700387397\",\n          \"pin\": \"56636\",\n          \"expireOn\": \"03.05.2018\"\n        },\n        \"created_at\": \"11.05.2017 17:12:05\",\n        \"nominal\": 5000,\n        \"type\": \"Sportmaster\"\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не найден заказ",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ по ms_order_id:6002 не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Не найдена карта",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Карта по ms_card_id:6002 не найдена\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/CardsController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/cards/list",
    "title": "1. Список карт с номиналом",
    "name": "Cards_list",
    "group": "Certificate_cards",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "cards",
            "description": "<p>Список карт</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.id",
            "description": "<p>ID сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.type",
            "description": "<p>Тип сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.id1c",
            "description": "<p>Идентификатор в 1С</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.title",
            "description": "<p>Заголовок</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.name",
            "description": "<p>Название</p>"
          },
          {
            "group": "Success 200",
            "type": "Number[]",
            "optional": false,
            "field": "card.nominals",
            "description": "<p>Номиналы</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "card.nominals_text",
            "description": "<p>Расшифровка номиналов</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.nominal_text.nominal",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.nominal_text.price",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.nominal_text.text",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.image",
            "description": "<p>Ссылка на изображение сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.is_allow_to_order",
            "description": "<p>Доступен для заказа (1 - да, 0 - нет)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.is_plastic",
            "description": "<p>Является пластиковой картой (1 - да, 0 - нет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "card.description",
            "description": "<p>HTML описание</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "card.order",
            "description": "<p>Порядок отображения</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"cards\": [\n    {\n      \"id\": 4,\n      \"type\": \"RedCube\",\n      \"id1c\": \"00000005943\",\n      \"title\": \"Красный куб\",\n      \"name\": \"Электронный подарочный сертификат магазинов Красный Куб\",\n      \"nominals\": [\n        300,\n        500,\n        1000,\n        1500,\n        2000,\n        3000\n      ],\n      \"nominals_text\": [\n        {\n          \"nominal\": 300,\n          \"price\": 300,\n          \"text\": 300\n        },\n        {\n          \"nominal\": 500,\n          \"price\": 500,\n          \"text\": 500\n        },\n        {\n          \"nominal\": 1000,\n          \"price\": 1000,\n          \"text\": 1000\n        },\n        {\n          \"nominal\": 1500,\n          \"price\": 1500,\n          \"text\": 1500\n        },\n        {\n          \"nominal\": 2000,\n          \"price\": 2000,\n          \"text\": 2000\n        },\n        {\n          \"nominal\": 3000,\n          \"price\": 3000,\n          \"text\": 3000\n        }\n      ],\n      \"image\": \"http://dabpums.f/media/cards/4.jpg\",\n      \"is_allow_to_order\": 1,\n      \"is_plastic\": 0,\n      \"description\": \"<p style=\\\"text-align: justify;\\\">Необычные сувениры, оригинальные вещи для украшения дома, яркие и стильные аксессуары или изящная посуда&hellip; Предоставь выбор другу, отправь подарочный сертификат &laquo;Красного Куба&raquo;.&nbsp;На сегодняшний день &laquo;Красный Куб&raquo; - это крупнейшая в России сеть магазинов подарков. Необычные элементы декора, использование материалов разных фактур и широкий выбор формата не оставят равнодушными даже самых взыскательных клиентов.</p>\\r\\n<p style=\\\"text-align: justify;\\\"><br /><strong>Срок действия: 1 год.</strong></p>\",\n      \"order\": null\n    },\n    {...}\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/CardsController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/orders/check",
    "title": "5. Детали заказа",
    "name": "Check_order",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_order_id",
            "description": "<p>Идентификатор заказа МС</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "external_order_id",
            "description": "<p>Внешний идентификатор заказа МС</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"ms_order_id\": 384,\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"order\": {\n    \"ms_order_id\": 384,\n    \"external_order_id\": null,\n    \"external_user\": null,\n    \"status\": \"ready\",\n    \"amount\": 500,\n    \"delivery_email\": \"ivanov-chel@mail.ru\",\n    \"created_at\": \"07.19.2017 14:46:45\",\n    \"items\": [\n      {\n        \"ms_card_id\": 14,\n        \"card\": \"Mvideo\",\n        \"id1c\": \"00000006628\",\n        \"nominal\": 500,\n        \"qty\": 1,\n        \"status\": \"ready\",\n        \"card_data\": {\n          \"number\": \"2999534846100\",\n          \"pin\": \"0988\",\n          \"expireOn\": \"2018-03-24\",\n          \"issueOn\": \"2016-03-24\"\n        }\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не найден заказ",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ не найден\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/OrdersController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/orders/create",
    "title": "3. Создание нового заказа",
    "name": "Create_order",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор авторизованного участника</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": false,
            "field": "is_allow_cancel",
            "description": "<p>Заказ возможно отменить в течении суток</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "items",
            "description": "<p>Массив с картами для заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "items.card",
            "description": "<p>Идентификатор карты</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "items.nominal",
            "description": "<p>Номинал карты</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "items.qty",
            "description": "<p>Количество</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "delivery_email",
            "description": "<p>E-mail адрес для отправки сертификата</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"is_allow_cancel\": false,\n  \"items\": [\n    {\n      \"card\": \"Mvideo\",\n      \"nominal\": 500,\n      \"qty\": 1\n    }\n  ],\n  \"profile_id\": 18804,\n  \"delivery_email\": \"ivanov@mail.ru\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"order\": {\n    \"ms_order_id\": 382,\n    \"profile_id\": 18804,\n    \"external_order_id\": null,\n    \"external_user\": null,\n    \"status\": \"new\",\n    \"status_label\": \"Новый\",\n    \"amount\": 500,\n    \"delivery_email\": \"ivanov@mail.ru\",\n    \"delivery_phone_mobile\": null,\n    \"delivery_address\": null,\n    \"is_allow_cancel\": true,\n    \"is_canceled\": null,\n    \"can_be_canceled\": true,\n    \"created_at\": \"11.06.2019\",\n    \"items\": [\n      {\n        \"ms_card_id\": 429,\n        \"card\": \"Mvideo\",\n        \"card_title\": \"М.Видео\",\n        \"card_name\": \"М.Видео\",\n        \"id1c\": \"00000006628; МСК00004937\",\n        \"nominal\": 500,\n        \"qty\": 1,\n        \"status\": \"new\",\n        \"status_label\": \"Новый\",\n        \"image\": \"https://api-showcase.msforyou.ru/media/cards/1.png\",\n        \"cards\": []\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не указан участник",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Для проведения заказа необходимо указать либо external_order_id, либо profile_id\"\n}",
          "type": "json"
        },
        {
          "title": "Участник не найден",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Участник не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Дублирование заказа",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ по external_order_id:$external_order_id уже зарегистрирован\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/OrdersController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/cards/download-blob",
    "title": "8. Получение сертификата в виде blob",
    "name": "Download_blob_certificate",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_order_id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"profile_id\": 8,\n  \"ms_order_id\": 19,\n  \"ms_card_id\": 27\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "File",
            "optional": false,
            "field": "file",
            "description": "<p>Файл в виде blob</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Не найден заказ",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ по ms_order_id:6002 не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Заказ не принадлежит участнику",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ не принадлежит участнику\"\n}",
          "type": "json"
        },
        {
          "title": "Cертификат не найден",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Cертификат не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Сертификат не относится к заказу",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Сертификат не относится к заказу\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/CardsController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/cards/download",
    "title": "7. Получение сертификата файлом",
    "name": "Download_certificate",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_order_id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"ms_order_id\": 19,\n  \"ms_card_id\": 24\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "url",
            "description": "<ul> <li>Ссылка на сертификат</li> </ul>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"url\": \"<link_to_cert>\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Запрещено формировать файл",
          "content": "HTTP/1.1 403 FORBIDDEN\n{\n  \"name\": \"Forbidden\",\n  \"message\": \"403 Forbidden. Use /catalog/api-v3/cards/download-blob instead\",\n  \"code\": 0,\n  \"status\": 403,\n  \"type\": \"yii\\\\web\\\\ForbiddenHttpException\"\n}",
          "type": "json"
        },
        {
          "title": "Не найден заказ",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ по ms_order_id:6002 не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Cертификат не найден",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Cертификат не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Сертификат не относится к заказу",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Сертификат не относится к заказу\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/CardsController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/orders/cancel",
    "title": "6. Отмена заказа",
    "name": "Order_cancel",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_order_id",
            "description": "<p>ID заказа</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"ms_order_id\": 258\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"order\": {\n    \"ms_order_id\": 384,\n    \"external_order_id\": null,\n    \"external_user\": null,\n    \"status\": \"ready\",\n    \"amount\": 500,\n    \"delivery_email\": \"ivanov-chel@mail.ru\",\n    \"created_at\": \"07.19.2017 14:46:45\",\n    \"items\": [\n      {\n        \"ms_card_id\": 14,\n        \"card\": \"Mvideo\",\n        \"id1c\": \"00000006628\",\n        \"nominal\": 500,\n        \"qty\": 1,\n        \"status\": \"ready\",\n        \"card_data\": {\n          \"number\": \"2999534846100\",\n          \"pin\": \"0988\",\n          \"expireOn\": \"2018-03-24\",\n          \"issueOn\": \"2016-03-24\"\n        }\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не найден заказ",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ по ms_order_id:6002 не найден\"\n}",
          "type": "json"
        },
        {
          "title": "Заказ невозможно отменить",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Заказ уже невозможно отменить\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/OrdersController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/orders/list",
    "title": "9. Список заказов",
    "name": "Orders_list",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "updated_from",
            "description": "<p>Дата обновления анкеты, что бы синхронизировать лишь новые данные</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"updated_from\": \"2019-05-08 23:31:05\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"orders\": [\n    {\n      \"ms_order_id\": 236,\n      \"profile_id\": 12378,\n      \"external_order_id\": null,\n      \"external_user\": null,\n      \"status\": \"ready\",\n      \"status_label\": \"Полностью выдан\",\n      \"amount\": 4500,\n      \"delivery_email\": \"poima17@mail.ru\",\n      \"delivery_phone_mobile\": null,\n      \"delivery_address\": null,\n      \"is_allow_cancel\": 0,\n      \"is_canceled\": 0,\n      \"can_be_canceled\": false,\n      \"created_at\": \"25.03.2019\",\n      \"updated_at\": \"2019-06-13 10:01:07\",\n      \"items\": [\n        {\n          \"ms_card_id\": 266,\n          \"external_ordered_card_id\": 10480,\n          \"card\": \"DetMir\",\n          \"card_title\": \"Детский мир\",\n          \"card_name\": \"Детский Мир\",\n          \"id1c\": \"00000007966\",\n          \"nominal\": 1000,\n          \"qty\": 1,\n          \"status\": \"ready\",\n          \"status_label\": \"Полностью выдан\",\n          \"image\": \"https://api-showcase.msforyou.ru/media/cards/6.jpeg\",\n          \"cards\": [\n            {\n              \"ms_card_id\": 281,\n              \"card_data\": {\n                \"track\": \"7770001050341547383=24027222740000000\",\n                \"expireOn\": \"2020-03-26\"\n              },\n              \"created_at\": \"26.03.2019 15:36:10\",\n              \"nominal\": 1000,\n              \"type\": \"DetMir\"\n            }\n          ]\n        },\n        {\n          \"ms_card_id\": 267,\n          \"external_ordered_card_id\": 10481,\n          \"card\": \"DetMir\",\n          \"card_title\": \"Детский мир\",\n          \"card_name\": \"Детский Мир\",\n          \"id1c\": \"00000007966\",\n          \"nominal\": 1500,\n          \"qty\": 1,\n          \"status\": \"ready\",\n          \"status_label\": \"Полностью выдан\",\n          \"image\": \"https://api-showcase.msforyou.ru/media/cards/6.jpeg\",\n          \"cards\": [\n            {\n              \"ms_card_id\": 282,\n              \"card_data\": {\n                \"track\": \"7770001061240025215=24027229050000000\",\n                \"expireOn\": \"2020-03-26\"\n              },\n              \"created_at\": \"26.03.2019 15:36:10\",\n              \"nominal\": 1500,\n              \"type\": \"DetMir\"\n            }\n          ]\n        },\n        {\n          \"ms_card_id\": 268,\n          \"external_ordered_card_id\": 10482,\n          \"card\": \"DetMir\",\n          \"card_title\": \"Детский мир\",\n          \"card_name\": \"Детский Мир\",\n          \"id1c\": \"00000007966\",\n          \"nominal\": 2000,\n          \"qty\": 1,\n          \"status\": \"ready\",\n          \"status_label\": \"Полностью выдан\",\n          \"image\": \"https://api-showcase.msforyou.ru/media/cards/6.jpeg\",\n          \"cards\": [\n            {\n              \"ms_card_id\": 283,\n              \"card_data\": {\n                \"track\": \"7770001051364260000=24017222290005555\",\n                \"expireOn\": \"2020-03-26\"\n              },\n              \"created_at\": \"26.03.2019 15:36:10\",\n              \"nominal\": 2000,\n              \"type\": \"DetMir\"\n            }\n          ]\n        }\n      ]\n    },\n    {\n      \"ms_order_id\": 237,\n      \"profile_id\": 12378,\n      \"external_order_id\": null,\n      \"external_user\": null,\n      \"status\": \"ready\",\n      \"status_label\": \"Полностью выдан\",\n      \"amount\": 1500,\n      \"delivery_email\": \"ivanov-chel@mail.ru\",\n      \"delivery_phone_mobile\": null,\n      \"delivery_address\": null,\n      \"is_allow_cancel\": 0,\n      \"is_canceled\": 0,\n      \"can_be_canceled\": false,\n      \"created_at\": \"25.03.2019\",\n      \"updated_at\": \"2019-06-13 10:01:07\",\n      \"items\": [\n        {\n          \"ms_card_id\": 269,\n          \"external_ordered_card_id\": 10483,\n          \"card\": \"DetMir\",\n          \"card_title\": \"Детский мир\",\n          \"card_name\": \"Детский Мир\",\n          \"id1c\": \"00000007966\",\n          \"nominal\": 1500,\n          \"qty\": 1,\n          \"status\": \"ready\",\n          \"status_label\": \"Полностью выдан\",\n          \"image\": \"https://api-showcase.msforyou.ru/media/cards/6.jpeg\",\n          \"cards\": [\n            {\n              \"ms_card_id\": 284,\n              \"card_data\": {\n                \"track\": \"7770001061241160000=24027225700005555\",\n                \"expireOn\": \"2020-03-26\"\n              },\n              \"created_at\": \"26.03.2019 15:37:07\",\n              \"nominal\": 1500,\n              \"type\": \"DetMir\"\n            }\n          ]\n        }\n      ]\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/OrdersController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/catalog/api-v3/users/orders",
    "title": "5. Список заказов пользователя",
    "name": "Profile_certificates_list",
    "group": "Certificate_cards",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "external_user",
            "description": "<p>Внешний ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date_from",
            "description": "<p>Дата начала интервала</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "date_to",
            "description": "<p>Дата окончания интервала</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"profile_id\": 8,\n  \"date_from\": \"14.05.2019\",\n  \"date_to\": \"21.07.2019\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "orders",
            "description": "<p>Заказы участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.ms_order_id",
            "description": "<p>ID заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_order_id",
            "description": "<p>Внешний ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_user",
            "description": "<p>Внешний участник</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.status",
            "description": "<p>Статус заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.status_label",
            "description": "<p>Расшифровка статуса заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.amount",
            "description": "<p>Сумма заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.delivery_email",
            "description": "<p>Данные доставка: email</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.delivery_phone_mobile",
            "description": "<p>Данные доставка: телефон</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.delivery_address",
            "description": "<p>Данные доставка: адрес</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.is_allow_cancel",
            "description": "<p>Возможность отменять заказ (1 - да, 0 - нет)</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.is_canceled",
            "description": "<p>Заказ отменен (1 - да, 0 - нет)</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "order.can_be_canceled",
            "description": "<p>Возможность отменить этот заказ (1 - да, 0 - нет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.created_at",
            "description": "<p>Время формирования заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.updated_at",
            "description": "<p>Время последнего обновления заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "order.items",
            "description": "<p>Состав заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items.ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items.external_ordered_card_id",
            "description": "<p>Внешний ID заказанного сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.card",
            "description": "<p>Тип карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.card_title",
            "description": "<p>Заголов</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.card_name",
            "description": "<p>Название</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.id1c",
            "description": "<p>Идентификатор в 1С</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items.nominal",
            "description": "<p>Номинал карты</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items.qty",
            "description": "<p>Количество карт</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.status",
            "description": "<p>Статус сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.status_label",
            "description": "<p>Расшифровка статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.image",
            "description": "<p>Ссылка на изображение сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "order.items.cards",
            "description": "<p>Состав сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items.cards.ms_card_id",
            "description": "<p>ID заказанного сертификата</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "order.items.cards.card_data",
            "description": "<p>Данные карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.cards.card_data.track",
            "description": "<p>Номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.cards.card_data.expireOn",
            "description": "<p>Срок действия</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.cards.created_at",
            "description": "<p>Время создания</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items.cards.nominal",
            "description": "<p>Номинал</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.items.cards.type",
            "description": "<p>Тип сертификата</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"orders\": [\n    {\n      \"ms_order_id\": 602,\n      \"profile_id\": 8,\n      \"external_order_id\": null,\n      \"external_user\": null,\n      \"status\": \"ready\",\n      \"status_label\": \"Полностью выдан\",\n      \"amount\": 5000,\n      \"delivery_email\": \"avs@pa-rus.net\",\n      \"delivery_phone_mobile\": null,\n      \"delivery_address\": null,\n      \"is_allow_cancel\": 0,\n      \"is_canceled\": 0,\n      \"can_be_canceled\": false,\n      \"created_at\": \"15.05.2019\",\n      \"updated_at\": \"2019-05-16 13:20:37\",\n      \"items\": [\n        {\n          \"ms_card_id\": 819,\n          \"external_ordered_card_id\": null,\n          \"card\": \"DetMir\",\n          \"card_title\": \"Детский мир\",\n          \"card_name\": \"Электронный подарочный сертификат магазинов Детский Мир\",\n          \"id1c\": \"00000007966\",\n          \"nominal\": 5000,\n          \"qty\": 1,\n          \"status\": \"ready\",\n          \"status_label\": \"Полностью выдан\",\n          \"image\": \"http://dabpums.f/media/cards/6.jpeg\",\n          \"cards\": [\n            {\n              \"ms_card_id\": 1426,\n              \"card_data\": {\n                \"track\": \"7770001054885834991=22097227960000000\",\n                \"expireOn\": \"2020-05-16\"\n              },\n              \"created_at\": \"16.05.2019 13:20:37\",\n              \"nominal\": 5000,\n              \"type\": \"DetMir\"\n            }\n          ]\n        }\n      ]\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-certificates-catalog/frontend/api/v3/controllers/UsersController.php",
    "groupTitle": "Certificate_cards"
  },
  {
    "type": "post",
    "url": "/courses/api/course/categories",
    "title": "1. Получение категорий обучающих курсов",
    "name": "Get_categories",
    "group": "Courses",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>ID участника</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "categories",
            "description": "<p>Список категорий обучающих курсов</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-courses/frontend/controllers/api/CourseController.php",
    "groupTitle": "Courses"
  },
  {
    "type": "post",
    "url": "/courses/api/course/category-list",
    "title": "2. Получение обучающих курсов по категории",
    "name": "Get_courses_by_categories",
    "group": "Courses",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "group_id",
            "description": "<p>ID категории курса</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "courses",
            "description": "<p>Список обучающих курсов по категории</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-courses/frontend/controllers/api/CourseController.php",
    "groupTitle": "Courses"
  },
  {
    "type": "GET",
    "url": "/location/api/cities/autocomplete",
    "title": "Получить список подсказок городов",
    "name": "CitiesAutocomplete",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "term",
            "description": "<p>Строка для поиска города. Опционально</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "region_id",
            "description": "<p>Идентификатор региона. Опционально</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"term\": \"Белгород\",\n  \"region_id\": 9\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String[]",
            "optional": false,
            "field": "cities",
            "description": "<p>Список подсказок городов</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"cities\": [\n    \"Белгород, Белгородская обл.\"\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-location/frontend/controllers/api/CitiesController.php",
    "groupTitle": "Location"
  },
  {
    "type": "GET",
    "url": "/location/api/cities/list",
    "title": "Получить список городов",
    "name": "CitiesList",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "term",
            "description": "<p>Строка для поиска города. Опционально</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "region_id",
            "description": "<p>Идентификатор региона. Опционально</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "limit",
            "description": "<p>Ограничить выборку количеством записей. Опционально</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "order_by",
            "description": "<p>Сортировать по полю. По умолчанию по названию города &quot;title&quot;, можно и по &quot;id&quot;</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"term\": \"Моск\",\n  \"region_id\": 1,\n  \"limit\": 15,\n  \"order_by\": \"title\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "cities",
            "description": "<p>Список городов</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"cities\": [\n    {\n      \"city\": \"Москва\",\n      \"city_id\": \"1\",\n      \"region\": \"Москва и Московская обл.\",\n      \"region_id\": \"1\"\n    },\n    {\n      \"city\": \"Московский\",\n      \"city_id\": \"94\",\n      \"region\": \"Москва и Московская обл.\",\n      \"region_id\": \"1\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-location/frontend/controllers/api/CitiesController.php",
    "groupTitle": "Location"
  },
  {
    "type": "get",
    "url": "/location/api/district/list",
    "title": "Получить список федеральных округов, районов и городов",
    "name": "District",
    "group": "Location",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "districts",
            "description": "<p>Данные по федеральным округам</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "districts.title",
            "description": "<p>Названиие ФО</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "districts.short",
            "description": "<p>Короткое название ФО</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "districts.name",
            "description": "<p>Альтернативное название ФО</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "districts.regions",
            "description": "<p>Данные по региону ФО</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "districts.regions.title",
            "description": "<p>Название региона</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "districts.regions.name",
            "description": "<p>Альтернативное название региона</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "districts.regions.cities",
            "description": "<p>Данные по населённым пунктам региона</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "districts.regions.cities.title",
            "description": "<p>Название населённого пункта</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"districts\": [\n     {\n         \"id\":8,\n         \"title\":\"Дальневосточный\",\n         \"short\":\"ДФО / ДВФО\",\n         \"name\":null,\n         \"regions\": [\n             {\n                 \"id\":5,\n                 \"title\":\"Амурская обл.\",\n                 \"name\":null,\n                 \"cities\": [\n                     {\n                         \"id\":286,\n                         \"title\":\"Айгунь\"\n                     },\n                     {\n                         \"id\":287,\n                         \"title\":\"Архара\"\n                     }\n                 ]\n             }\n         ]\n     }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-location/frontend/controllers/api/DistrictController.php",
    "groupTitle": "Location"
  },
  {
    "type": "get",
    "url": "/location/api/regions/list",
    "title": "Получить список регионов",
    "name": "RegionsList",
    "group": "Location",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "cities",
            "description": "<p>Признак включения городов в каждый регион. Опционально.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"regions\": [\n    {\n      \"id\": 97,\n      \"title\": \"NULL\",\n      \"name\": null,\n      \"name2\": null,\n      \"district_id\": null\n    },\n    {\n      \"id\": 3,\n      \"title\": \"Адыгея\",\n      \"name\": null,\n      \"name2\": null,\n      \"district_id\": 3\n    },\n    {\n      \"id\": 4,\n      \"title\": \"Алтайский край\",\n      \"name\": \"Алтай\",\n      \"name2\": \"Алтайский\",\n      \"district_id\": 7\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-location/frontend/controllers/api/RegionsController.php",
    "groupTitle": "Location"
  },
  {
    "type": "post",
    "url": "/mobile/api/firebase/register-device",
    "title": "Зарегистрировать устройство для PUSH-уведомлений",
    "name": "RegisterDevice",
    "group": "Mobile",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "platform",
            "description": "<p>Платформа устройства: web / ios / android</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Токен устройства, зарегистрированного в Firebase Cloud Messaging (FCM)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n    \"profile_id\": 937,\n    \"platform\": \"web\",\n    \"token\": \"dNhNz_PI-kM:APA91bG4AyyQqmQUbdWRJ041K93r75UFuHJJUuuNO8c3623NVBx4h4CLW4tjWL8R7UPchEaG_9wi2qho_ocT4dUbqSKKthuFm1Wd2BfM1s5yMb6wsnD17wViVtiAoiwdy1-J22aQejgO\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n    \"result\": \"OK\",\n    \"gcm\": {\n        \"platform\": \"web\",\n        \"token\": \"dNhNz_PI-kM:APA91bG4AyyQqmQUbdWRJ041K93r75UFuHJJUuuNO8c3623NVBx4h4CLW4tjWL8R7UPchEaG_9wi2qho_ocT4dUbqSKKthuFm1Wd2BfM1s5yMb6wsnD17wViVtiAoiwdy1-J22aQejgO\",\n        \"profile_id\": 937,\n        \"created_at\": \"2018-11-24 18:04:59\"\n    }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-mobile/frontend/controllers/api/FirebaseController.php",
    "groupTitle": "Mobile"
  },
  {
    "type": "post",
    "url": "/payments/api-v3/payments/calculate",
    "title": "1. Размера комиссии по сумме платежа",
    "name": "GetCommission",
    "group": "Payments",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип платежа</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>Сумма платежа</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"type\": \"webmoney\",\n  \"amount\": 10000\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>Сумма</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Комиссия</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "commission",
            "description": "<p>Размер комиссии</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"type\": \"webmoney\",\n  \"amount\": 10000,\n  \"message\": \"7%\",\n  \"commission\": 700\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Переданы не все параметры",
          "content": "HTTP/1.1 400 BAD\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Необходимо передать параметры: type, amount\"\n}",
          "type": "json"
        },
        {
          "title": "Не найдены настройки по типу",
          "content": "HTTP/1.1 400 BAD\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найдены настройки по типу webmoneys\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-prizes-payments/frontend/api/v3/controllers/PaymentsController.php",
    "groupTitle": "Payments"
  },
  {
    "type": "post",
    "url": "/payments/api-v3/payments/check",
    "title": "3. Проверка статуса платежа",
    "name": "PaymentCheck",
    "group": "Payments",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "ms_payment_id",
            "description": "<p>ID платежа</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "payment_id",
            "description": "<p>Внешний ID платежа</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса",
          "content": "{\n  \"ms_payment_id\": 225\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "payment",
            "description": "<p>Платеж</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.payment_id",
            "description": "<p>Идентификатор платежа ВНЕШНИЙ</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.ms_payment_id",
            "description": "<p>Идентификатор платежа внутренний</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "payment.amount",
            "description": "<p>Сумма перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type_label",
            "description": "<p>Название перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status",
            "description": "<p>Статус платежа: new (Ожидает обработки), waiting (Ожидает подтверждения), processing (Передан в обработку), success (Успешно выполнен), fail (Ошибка при выполнении), rollback (Откат, возвращен), 1c_blocked (Заблокирован в 1С)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status_label",
            "description": "<p>Название статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.created_at",
            "description": "<p>Дата и время создания платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment.parameters",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.last_name",
            "description": "<p>Фамилия участника</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Не найден платеж по ID",
          "content": "HTTP/1.1 400 BAD\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найден платеж ms_payment_id=100975\"\n}",
          "type": "json"
        },
        {
          "title": "Не найден платеж по Внешнему ID",
          "content": "HTTP/1.1 400 BAD\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найден платеж payment_id=100975\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-prizes-payments/frontend/api/v3/controllers/PaymentsController.php",
    "groupTitle": "Payments"
  },
  {
    "type": "post",
    "url": "/payments/api-v3/payments/by-profile",
    "title": "4. Список платежей по конкретному участнику",
    "name": "PaymentsByProfile",
    "group": "Payments",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор авторизованного участника</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_id\": 4063\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"payments\": [\n    {\n      \"payment_id\": null,\n      \"ms_payment_id\": 42,\n      \"profile_id\": 4063,\n      \"amount\": 10,\n      \"type\": \"phone\",\n      \"type_label\": \"Мобильный телефон\",\n      \"status\": \"paid\",\n      \"status_label\": \"Успешно выполнен\",\n      \"parameters\": {\n        \"phone_mobile\": \"+79507440000\",\n        \"first_name\": \"Юрий\",\n        \"last_name\": \"Новосел\"\n      },\n      \"created_at\": \"17.08.2018 09:21:10\"\n    }\n  ]\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payments",
            "description": "<p>Список платежей и переводов</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.payment_id",
            "description": "<p>Идентификатор платежа ВНЕШНИЙ</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.ms_payment_id",
            "description": "<p>Идентификатор платежа внутренний</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "payment.amount",
            "description": "<p>Сумма перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type_label",
            "description": "<p>Название перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status",
            "description": "<p>Статус платежа: new (Ожидает обработки), waiting (Ожидает подтверждения), processing (Передан в обработку), success (Успешно выполнен), fail (Ошибка при выполнении), rollback (Откат, возвращен), 1c_blocked (Заблокирован в 1С)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status_label",
            "description": "<p>Название статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.created_at",
            "description": "<p>Дата и время создания платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment.parameters",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.last_name",
            "description": "<p>Фамилия участника</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Не найден участник",
          "content": "HTTP/1.1 400 BAD\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найден участник #100975\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-prizes-payments/frontend/api/v3/controllers/PaymentsController.php",
    "groupTitle": "Payments"
  },
  {
    "type": "post",
    "url": "/payments/api-v3/payments/create",
    "title": "2. Создание платежа участником",
    "name": "PaymentsCreate",
    "group": "Payments",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор авторизованного участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>Сумма перевода. Максимум 14500 за раз</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "parameters",
            "description": "<p>Параметры с номером</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_id\": 107,\n  \"type\": \"phone\",\n  \"amount\": 500,\n  \"parameters\": {\n    \"phone_mobile\": \"+79299004020\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"payment\": {\n    \"payment_id\": null,\n    \"ms_payment_id\": 2662,\n    \"profile_id\": 107,\n    \"amount\": 535,\n    \"type\": \"phone\",\n    \"type_label\": \"Мобильный телефон\",\n    \"status\": \"new\",\n    \"status_label\":\"Ожидает обработки\",\n    \"parameters\": {\n      \"phone_mobile\": \"+79299004020\",\n      \"first_name\": \"Михаил\",\n      \"last_name\": \"Скородумов\"\n    },\n    \"created_at\": \"13.06.2019 15:23:30\"\n  }\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.payment_id",
            "description": "<p>Идентификатор платежа ВНЕШНИЙ</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.ms_payment_id",
            "description": "<p>Идентификатор платежа внутренний</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "payment.amount",
            "description": "<p>Сумма перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type_label",
            "description": "<p>Название перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status",
            "description": "<p>Статус платежа: new (Ожидает обработки), waiting (Ожидает подтверждения), processing (Передан в обработку), success (Успешно выполнен), fail (Ошибка при выполнении), rollback (Откат, возвращен), 1c_blocked (Заблокирован в 1С)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status_label",
            "description": "<p>Название статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.created_at",
            "description": "<p>Дата и время создания платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment.parameters",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.last_name",
            "description": "<p>Фамилия участника</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-prizes-payments/frontend/api/v3/controllers/PaymentsController.php",
    "groupTitle": "Payments"
  },
  {
    "type": "post",
    "url": "/payments/api-v3/payments/create-external",
    "title": "Создание платежа ВНЕШНИМ участником",
    "name": "PaymentsCreateExternal",
    "group": "Payments",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "profile_model",
            "description": "<p>Массив с данными внешнего участника</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_model.id",
            "description": "<p>ID участника внешний</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.first_name",
            "description": "<p>Имя</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.last_name",
            "description": "<p>Фамилия</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "profile_model.middle_name",
            "description": "<p>Отчество</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "profile_model.phone_mobile_local",
            "description": "<p>Номер телефона</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "profile_model.email",
            "description": "<p>E-mail адрес</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "amount",
            "description": "<p>Сумма перевода. Максимум 14500 за раз</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "payment_id",
            "description": "<p>Идендификатор платежа ВНЕШНИЙ. Что бы исключить дублирование</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "parameters",
            "description": "<p>Параметры с номером</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_model\": {\n    \"id\": 18804,\n    \"first_name\": \"Иван\",\n    \"last_name\": \"Иванов\",\n    \"middle_name\": \"Иванович\",\n    \"phone_mobile_local\": \"+79299004020\",\n    \"email\": \"ivanov-chel@mail.ru\"\n  },\n  \"type\": \"card\",\n  \"amount\": 500,\n  \"payment_id\": \"1004\",\n  \"parameters\": {\n    \"phone_mobile\": \"3456345634560000\"\n    \"first_name\": \"Иван\",\n    \"last_name\": \"Иванов\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"payment\": {\n    \"payment_id\": \"1004\",\n    \"ms_payment_id\": 2661,\n    \"profile_id\": 18804,\n    \"amount\": 575,\n    \"type\": \"card\",\n    \"type_label\": \"Банковская карта\",\n    \"status\": \"new\",\n    \"status_label\":\"Ожидает обработки\",\n    \"parameters\": {\n      \"phone_mobile\": \"3456345634560000\",\n      \"first_name\": \"Иван\",\n      \"last_name\": \"Иванов\",\n    },\n    \"created_at\": \"13.06.2019 15:03:19\"\n  }\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.payment_id",
            "description": "<p>Идентификатор платежа ВНЕШНИЙ</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.ms_payment_id",
            "description": "<p>Идентификатор платежа внутренний</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "payment.amount",
            "description": "<p>Сумма перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type_label",
            "description": "<p>Название перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status",
            "description": "<p>Статус платежа: new (Ожидает обработки), waiting (Ожидает подтверждения), processing (Передан в обработку), success (Успешно выполнен), fail (Ошибка при выполнении), rollback (Откат, возвращен), 1c_blocked (Заблокирован в 1С)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status_label",
            "description": "<p>Название статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.created_at",
            "description": "<p>Дата и время создания платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment.parameters",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.last_name",
            "description": "<p>Фамилия участника</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-prizes-payments/frontend/api/v3/controllers/PaymentsController.php",
    "groupTitle": "Payments"
  },
  {
    "type": "post",
    "url": "/payments/api-v3/payments/list",
    "title": "5. Список всех платежей",
    "name": "PaymentsList",
    "group": "Payments",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "updated_from",
            "description": "<p>Дата обновления анкеты, что бы синхронизировать лишь новые данные</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"updated_from\": \"2019-05-08 23:31:05\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"payments\": [\n    {\n      \"payment_id\": null,\n      \"ms_payment_id\": 42,\n      \"profile_id\": 4063,\n      \"amount\": 10,\n      \"type\": \"phone\",\n      \"type_label\": \"Мобильный телефон\",\n      \"status\": \"paid\",\n      \"status_label\": \"Успешно выполнен\",\n      \"parameters\": {\n        \"phone_mobile\": \"+79507440000\",\n        \"first_name\": \"Юрий\",\n        \"last_name\": \"Новосел\"\n      },\n      \"created_at\": \"17.08.2018 09:21:10\",\n      \"updated_at\": \"10.06.2019 01:15:04\"\n    },\n    {\n      \"payment_id\": \"1808\",\n      \"ms_payment_id\": 43,\n      \"profile_id\": 11666,\n      \"amount\": 182,\n      \"type\": \"qiwi\",\n      \"type_label\": \"Qiwi-кошелек\",\n      \"status\": \"new\",\n      \"status_label\": \"Ожидает обработки\",\n      \"parameters\": {\n        \"phone_mobile\": \"+79510873000\",\n        \"first_name\": \"Артем\",\n        \"last_name\": \"Чурилов\"\n      },\n      \"created_at\": \"25.08.2018 13:15:12\",\n      \"updated_at\": \"09.06.2019 03:25:10\"\n    }\n  ]\n}",
          "type": "json"
        }
      ],
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payments",
            "description": "<p>Список платежей и переводов</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.payment_id",
            "description": "<p>Идентификатор платежа ВНЕШНИЙ</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.ms_payment_id",
            "description": "<p>Идентификатор платежа внутренний</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "payment.amount",
            "description": "<p>Сумма перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type",
            "description": "<p>Тип перевода: phone (Номер телефона), yandex (Яндекс.Деньги), webmoney (Webmoney), qiwi (Qiwi-кошелек), card (Банковская карта), rbs (Расчетный банковский счет)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.type_label",
            "description": "<p>Название перевода</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status",
            "description": "<p>Статус платежа: new (Ожидает обработки), waiting (Ожидает подтверждения), processing (Передан в обработку), success (Успешно выполнен), fail (Ошибка при выполнении), rollback (Откат, возвращен), 1c_blocked (Заблокирован в 1С)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.status_label",
            "description": "<p>Название статуса</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.created_at",
            "description": "<p>Дата и время создания платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "payment.parameters",
            "description": "<p>Детали платежа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.phone_mobile",
            "description": "<p>Номер телефона, номер счета, номер карты</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "payment.parameters.last_name",
            "description": "<p>Фамилия участника</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-prizes-payments/frontend/api/v3/controllers/PaymentsController.php",
    "groupTitle": "Payments"
  },
  {
    "type": "post",
    "url": "/profiles/api/auth/get-profile",
    "title": "Получить информацию по участнику",
    "name": "GetProfile",
    "group": "Profiles",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_id\": 12\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "profile",
            "description": "<p>Данные по участнику</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.full_name",
            "description": "<p>Полное имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.last_name",
            "description": "<p>Фамилия участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.middle_name",
            "description": "<p>Отчество участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.role",
            "description": "<p>Роль участника (rtt=РТТ, dealer=Дилер)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.specialty",
            "description": "<p>Должность участника (leader=руководитель или manager=продавец)</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.avatar_url",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.phone_mobile",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.email",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.registered_at",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.created_at",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.blocked_at",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.blocked_reason",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.banned_at",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.banned_reason",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "profile.account",
            "description": "<p>Налоговая анкета</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.account.status",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "profile.account.status_label",
            "description": ""
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "result",
            "description": "<p>OK при успешном запросе</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n    \"result\": \"OK\",\n    \"profile\": {\n        \"profile_id\": 12,\n        \"full_name\": \"Иван Иванов\",\n        \"first_name\": \"Иван\",\n        \"last_name\": \"Иванов\",\n        \"middle_name\": \"Иванович\",\n        \"role\": null,\n        \"specialty\": null,\n        \"avatar_url\": \"https://polaris.msforyou.ru/images/avatar_blank.png?v=3\",\n        \"phone_mobile\": \"+79299004002\",\n        \"email\": null,\n        \"balance\": 0,\n        \"dealer_id\": 11,\n        \"dealer\": {\n            \"id\": 11,\n            \"name\": \"Планета\",\n            \"address\": \"426006, Удмуртская Респ, Ижевск г, Клубная ул, дом № 37\",\n            \"class\": \"C\",\n            \"inn\": \"\",\n            \"type\": \"rtt\"\n        },\n        \"registered_at\": null,\n        \"checked_at\": null,\n        \"pers_at\": null,\n        \"created_at\": \"2019-08-05 21:49:17\",\n        \"blocked_at\": null,\n        \"blocked_reason\": null,\n        \"banned_at\": null,\n        \"banned_reason\": null,\n        \"account\": null\n    }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/modules/profiles/frontend/controllers/api/AuthController.php",
    "groupTitle": "Profiles"
  },
  {
    "type": "post",
    "url": "/profiles/api/profiles/load-avatar",
    "title": "Загрузка аватарки",
    "name": "LoadAvatar",
    "group": "Profiles",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника TM или RD</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "image",
            "description": "<p>Строка Base-64 c изображением</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n    \"profile_id\": 941,\n    \"image\": \"data:image/jpg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n    \"result\": \"OK\",\n    \"profile\": {\n        \"id\": 2,\n        \"profile_id\": 2,\n        \"full_name\": \"Денис Трофимов\",\n        \"first_name\": \"Денис\",\n        \"last_name\": \"Трофимов\",\n        \"middle_name\": \"Михайлович\",\n        \"role\": \"tm\",\n        \"gender\": null,\n        \"city_id\": null,\n        \"dealer_id\": null,\n        \"city_local\": null,\n        \"specialty\": null,\n        \"avatar_url\": \"https://oos.f/data/photos/profile_2_avatar_5cb9b6d55ebfa.jpg\",\n        \"phone_mobile\": \"+79299004003\",\n        \"email\": \"7binary@list.ru\",\n        \"balance\": 0,\n        \"birthday_on\": null,\n        \"registered_at\": \"2019-03-27 15:42:27\",\n        \"checked_at\": null,\n        \"pers_at\": null,\n        \"created_at\": \"2019-03-26 09:22:29\",\n        \"blocked_at\": null,\n        \"blocked_reason\": null,\n        \"banned_at\": null,\n        \"banned_reason\": null,\n        \"account\": null,\n        \"sales_point_ids\": [],\n        \"region_ids\": [\n            1\n        ],\n        \"district_ids\": []\n    }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/modules/profiles/frontend/controllers/api/ProfilesController.php",
    "groupTitle": "Profiles"
  },
  {
    "type": "post",
    "url": "/profiles/api/transaction/list",
    "title": "Транзакции участника",
    "name": "TransactionList",
    "group": "Profiles",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_id\": 12\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n    \"result\": \"OK\",\n    \"transactions\": [\n        {\n            \"id\": 15,\n            \"amount\": 3000,\n            \"balance_after\": 0,\n            \"balance_before\": 3000,\n            \"title\": \"Откат баллов за бонус #100 от 19.07.2019\",\n            \"comment\": \"\",\n            \"type\": \"out\",\n            \"created_at\": \"06.08.2019 22:01:27\"\n        },\n        {\n            \"id\": 11,\n            \"amount\": 3000,\n            \"balance_after\": 3000,\n            \"balance_before\": 0,\n            \"title\": \"Зачисление баллов за бонус #100 от 19.07.2019\",\n            \"comment\": \"\",\n            \"type\": \"in\",\n            \"created_at\": \"05.08.2019 21:49:17\"\n        }\n    ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/modules/profiles/frontend/controllers/api/TransactionController.php",
    "groupTitle": "Profiles"
  },
  {
    "type": "post",
    "url": "/profiles/api/register/autocomplete-company",
    "title": "Поиск компании по названию",
    "name": "AutocompleteCompany",
    "group": "Register",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>Название компании</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "city_id",
            "description": "<p>Идентификатор города, опционально</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "type",
            "description": "<p>Тип компании: rtt или dealer</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": true,
            "field": "limit",
            "description": "<p>Лимит записей, опционально. По умолчанию 20</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"name\": \"Ромашка\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"dealers\": [\n    {\n      \"id\": \"7\",\n      \"name\": \"ООО Ромашка\",\n      \"type\": \"rtt\",\n      \"address\": \"Москва, Ленинградское шоссе, д 52\",\n      \"class\": \"A\",\n      \"inn\": \"123456123456\"\n    },\n    {\n      \"id\": \"8\",\n      \"name\": \"Ромашка другая\",\n      \"type\": \"dealer\",\n      \"address\": \"Москва, Беломорская, д 18\",\n      \"class\": \"B\",\n      \"inn\": \"112233445566\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/modules/profiles/frontend/controllers/api/RegisterController.php",
    "groupTitle": "Register"
  },
  {
    "type": "post",
    "url": "/profiles/api/register",
    "title": "Регистрация участника",
    "name": "Index",
    "group": "Register",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Токен регистрации</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone",
            "description": "<p>Номер телефона участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "email",
            "description": "<p>E-mail адрес участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "last_name",
            "description": "<p>Фамилия участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "first_name",
            "description": "<p>Имя участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "middle_name",
            "description": "<p>Отчество телефона</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "dealer_id",
            "description": "<p>Идентификатор компании</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "specialty",
            "description": "<p>Должность участника. Для РТТ участников: rtt_leader (Руководитель в точке продаж) или rtt_manager (Продавец в точке продаж). Для участников Дилера: dealer_leader (Руководитель отдела продаж) или dealer_manager (Менеджер отдела продаж)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>Пароль (строго от 6 символов)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "passwordConfirm",
            "description": "<p>Подтверждение пароля (строго от 6 символов)</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": false,
            "field": "checkedRules",
            "description": "<p>Согласие с правилами акции (обязательно согласие)</p>"
          },
          {
            "group": "Parameter",
            "type": "Boolean",
            "optional": false,
            "field": "checkedPers",
            "description": "<p>Согласие на обработку персональных данных (обязательно согласие)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"token\": \"abcdefg12345\",\n  \"phone\": \"+79299004008\",\n  \"last_name\": \"Волков\",\n  \"first_name\": \"Сергей\",\n  \"middle_name\": \"Александрович\",\n  \"email\": \"7binary@list.ru\",\n  \"dealer_id\": 7,\n  \"specialty\": \"dealer_leader\",\n  \"password\": \"123123\",\n  \"passwordConfirm\": \"123123\",\n  \"checkedRules\": true,\n  \"checkedPers\": true\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"profile_id\": 10,\n  \"login\": \"+79299004008\",\n  \"token\": \"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwcm9maWxlX2lkIjoxMH0.NDZlNDRhY2U3MGY4N2FlMDhiZGIxZGE2MDg5NTY1NTgyYmM4NjQyODdkM2ExMTA4ZDdmMDEzZjE4MjA4YTFmNA\",\n  \"logged_at\": \"2019-08-05 14:23:09\",\n  \"logged_ip\": \"127.0.0.1\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/modules/profiles/frontend/controllers/api/RegisterController.php",
    "groupTitle": "Register"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/category/list",
    "title": "4. Список всех категорий",
    "name": "Categories_list",
    "group": "Shop",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "categories",
            "description": "<p>Категории</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "category.id",
            "description": "<p>ID категории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "category.name",
            "description": "<p>Название категории</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"categories\": [\n    {\n      \"id\": 1,\n      \"name\": \"Cats\"\n    },\n    {\n      \"id\": 2,\n      \"name\": \"Dogs\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/CategoryController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/category/view",
    "title": "5. Просмотр категории",
    "name": "Category_View",
    "group": "Shop",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "category_id",
            "description": "<p>ID категории</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"category_id\": 2\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "category",
            "description": "<p>Категории</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "category.id",
            "description": "<p>ID категории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "category.name",
            "description": "<p>Название категории</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"category\": {\n    \"id\": 2,\n    \"name\": \"Dogs\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не указан параметр",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не указан обязательный параметр category_id\"\n}",
          "type": "json"
        },
        {
          "title": "Категория не найдена",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найдена категория с ID 123\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/CategoryController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/order/create",
    "title": "1. Создание заказа",
    "name": "Order_Create",
    "group": "Shop",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "external_order_id",
            "description": "<p>Внешний ID заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "external_user_id",
            "description": "<p>Внешний ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "phone_mobile",
            "description": "<p>Мобильный телефон участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>E-mail участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "delivery_address",
            "description": "<p>Адрес доставки</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "items",
            "description": "<p>Товары заказа</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "item.id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "item.qty",
            "description": "<p>Количество</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "item.size",
            "description": "<p>Размер</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"external_order_id\": \"0012\",\n  \"external_user_id\": \"3321\",\n  \"phone_mobile\": \"9151913532\",\n  \"email\": \"nd@msforyou.ru\",\n  \"delivery_address\": \"Адрес доставки\",\n  \"items\": [\n    {\n      \"id\": 1,\n      \"qty\": 1,\n      \"size\": \"S\"\n    }\n  ]\n}",
          "type": "json"
        },
        {
          "title": "Или:",
          "content": "{\n  \"profile_id\": 149,\n  \"phone_mobile\": \"9151913532\",\n  \"email\": \"nd@msforyou.ru\",\n  \"delivery_address\": \"Адрес доставки\",\n  \"items\": [\n    {\n      \"id\": 2,\n      \"qty\": 1,\n      \"size\": \"M\"\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "order",
            "description": "<p>Заказ</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.id",
            "description": "<p>ID Заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_order_id",
            "description": "<p>Внешний ID заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_user_id",
            "description": "<p>Внешний ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.status",
            "description": "<p>Статус заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.phone_mobile",
            "description": "<p>Мобильный телефон участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.email",
            "description": "<p>E-mail участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.delivery_address",
            "description": "<p>Адрес доставки</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items_cost",
            "description": "<p>Сумма заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.delivery_cost",
            "description": "<p>Сумма доставки</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.total_cost",
            "description": "<p>Итоговая сумма</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.comment",
            "description": "<p>Комментарий к заказу</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.created",
            "description": "<p>Время создания заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "order.items",
            "description": "<p>Товары заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.id",
            "description": "<p>ID позиции заказов</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product_id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.size",
            "description": "<p>Размер товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "item.price",
            "description": "<p>Цена</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.qty",
            "description": "<p>Количество</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item.product",
            "description": "<p>Товар</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.name",
            "description": "<p>Название товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "item.product.price",
            "description": "<p>Цена товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.picture",
            "description": "<p>Изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.picture_url",
            "description": "<p>Ссылка на изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.position",
            "description": "<p>Позиция товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item.product.category",
            "description": "<p>Категория товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.category.id",
            "description": "<p>ID категории товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.category.name",
            "description": "<p>Название категории товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "item.product.sizes",
            "description": "<p>Размеры товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.description",
            "description": "<p>Описание товара</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"order\": {\n    \"order_id\": 4,\n    \"external_order_id\": \"3012\",\n    \"external_user_id\": \"3321\",\n    \"profile_id\": null,\n    \"status\": \"new\",\n    \"phone_mobile\": \"+79151913532\",\n    \"email\": \"nd@msforyou.ru\",\n    \"delivery_address\": \"Адрес доставки\",\n    \"items_cost\": 125,\n    \"delivery_cost\": 0,\n    \"total_cost\": 125,\n    \"comment\": null,\n    \"created\": \"23.04.2019 18:38\",\n    \"items\": [\n      {\n        \"id\": 5,\n        \"product_id\": 1,\n        \"size\": \"S\",\n        \"price\": 25,\n        \"qty\": 1,\n        \"product\": {\n          \"id\": 1,\n          \"name\": \"Product\",\n          \"price\": 25,\n          \"picture\": null,\n          \"picture_url\": null,\n          \"position\": 0,\n          \"category\": {\n            \"id\": 1,\n            \"name\": \"Cats\"\n          },\n          \"sizes\": [],\n          \"description\": null\n        }\n      },\n      {\n        \"id\": 6,\n        \"product_id\": 2,\n        \"size\": null,\n        \"price\": 50,\n        \"qty\": 2,\n        \"product\": {\n          \"id\": 2,\n          \"name\": \"Thing\",\n          \"price\": 50,\n          \"picture\": null,\n          \"picture_url\": null,\n          \"position\": 0,\n          \"category\": {\n            \"id\": 2,\n            \"name\": \"Dogs\"\n          },\n          \"sizes\": [],\n          \"description\": null\n        }\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не указан Профиль участника или Внешний ID заказа",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"profile_id\": \"Необходимо заполнить «ID Участника».\",\n    \"external_order_id\": \"Необходимо заполнить «Внешний ID заказа».\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Внешний ID заказа передан как число",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"external_order_id\": \"Значение «Внешний ID заказа» должно быть строкой.\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Повторный Внешний ID заказа",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"external_order_id\": \"Значение «123» для «Внешний ID заказа» уже занято.\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не указан телефон",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"phone_mobile\": \"Необходимо заполнить «Телефон».\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не указан E-mail",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"email\": \"Необходимо заполнить «E-mail».\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не указан адрес доставки",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"delivery_address\": \"Необходимо заполнить «Адрес доставки».\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не указаны товары",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"items\": \"Необходимо заполнить массив товаров items[{id, qty}]\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не указан ID товара",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"items\": \"Не указан ID товара items[{id}]\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не указано количество",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"items\": \"Не указано количество товаров items[{qty}]\"\n  }\n}",
          "type": "json"
        },
        {
          "title": "Не найден товар",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"errors\": {\n    \"items\": \"Не найден товар с ID 222\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/OrderController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/order/view",
    "title": "2. Просмотр заказа",
    "name": "Order_View",
    "group": "Shop",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>ID заказа в системе</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "external_order_id",
            "description": "<p>Внешний ID заказа в</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"order_id\": 4\n}",
          "type": "json"
        },
        {
          "title": "Или:",
          "content": "{\n  \"external_order_id\": \"3012\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "order",
            "description": "<p>Заказ</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.id",
            "description": "<p>ID Заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_order_id",
            "description": "<p>Внешний ID заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_user_id",
            "description": "<p>Внешний ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.status",
            "description": "<p>Статус заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.phone_mobile",
            "description": "<p>Мобильный телефон участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.email",
            "description": "<p>E-mail участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.delivery_address",
            "description": "<p>Адрес доставки</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items_cost",
            "description": "<p>Сумма заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.delivery_cost",
            "description": "<p>Сумма доставки</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.total_cost",
            "description": "<p>Итоговая сумма</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.comment",
            "description": "<p>Комментарий к заказу</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.created",
            "description": "<p>Время создания заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "order.items",
            "description": "<p>Товары заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.id",
            "description": "<p>ID позиции заказов</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product_id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.size",
            "description": "<p>Размер товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "item.price",
            "description": "<p>Цена</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.qty",
            "description": "<p>Количество</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item.product",
            "description": "<p>Товар</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.name",
            "description": "<p>Название товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "item.product.price",
            "description": "<p>Цена товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.picture",
            "description": "<p>Изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.picture_url",
            "description": "<p>Ссылка на изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.position",
            "description": "<p>Позиция товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item.product.category",
            "description": "<p>Категория товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.category.id",
            "description": "<p>ID категории товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.category.name",
            "description": "<p>Название категории товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "item.product.sizes",
            "description": "<p>Размеры товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.description",
            "description": "<p>Описание товара</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"order\": {\n    \"order_id\": 4,\n    \"external_order_id\": \"3012\",\n    \"external_user_id\": \"3321\",\n    \"profile_id\": null,\n    \"status\": \"new\",\n    \"phone_mobile\": \"+79151913532\",\n    \"email\": \"nd@msforyou.ru\",\n    \"delivery_address\": \"Адрес доставки\",\n    \"items_cost\": 125,\n    \"delivery_cost\": 0,\n    \"total_cost\": 125,\n    \"comment\": null,\n    \"created\": \"23.04.2019 18:38\",\n    \"items\": [\n      {\n        \"id\": 5,\n        \"product_id\": 1,\n        \"size\": \"S\",\n        \"price\": 25,\n        \"qty\": 1,\n        \"product\": {\n          \"id\": 1,\n          \"name\": \"Product\",\n          \"price\": 25,\n          \"picture\": null,\n          \"picture_url\": null,\n          \"position\": 0,\n          \"category\": {\n            \"id\": 1,\n            \"name\": \"Cats\"\n          },\n          \"sizes\": [],\n          \"description\": null\n        }\n      },\n      {\n        \"id\": 6,\n        \"product_id\": 2,\n        \"size\": null,\n        \"price\": 50,\n        \"qty\": 2,\n        \"product\": {\n          \"id\": 2,\n          \"name\": \"Thing\",\n          \"price\": 50,\n          \"picture\": null,\n          \"picture_url\": null,\n          \"position\": 0,\n          \"category\": {\n            \"id\": 2,\n            \"name\": \"Dogs\"\n          },\n          \"sizes\": [],\n          \"description\": null\n        }\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не указаны параметры",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не указан обязательный параметр order_id или external_order_id\"\n}",
          "type": "json"
        },
        {
          "title": "Не найден заказ по order_id",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найден заказ с ID 2121\"\n}",
          "type": "json"
        },
        {
          "title": "Не найден заказ по external_order_id",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найден заказ с external_order_id 171\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/OrderController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/product/statuses",
    "title": "8. Список статусов заказов",
    "name": "Order_statuses_list",
    "group": "Shop",
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"new\": \"Новый заказ\",\n  \"delivery\": \"Доставляется\",\n  \"completed\": \"Полностью обработан\",\n  \"rollback\": \"Откат Покупки\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/OrderController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/product/view",
    "title": "7. Просмотр товара",
    "name": "Product_View",
    "group": "Shop",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "prodict_id",
            "description": "<p>ID товара</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"prodict_id\": 2\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "product",
            "description": "<p>Товар</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product.id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.vendor_code",
            "description": "<p>Артикул</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>Название товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.model",
            "description": "<p>Модель</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "product.price",
            "description": "<p>Цена товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.picture",
            "description": "<p>Изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.picture_url",
            "description": "<p>Ссылка на изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product.position",
            "description": "<p>Позиция товара в категории</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "product.category",
            "description": "<p>Категория товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product.category.id",
            "description": "<p>ID категории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.category.name",
            "description": "<p>Название категории</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "product.sizes",
            "description": "<p>Размеры товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.description",
            "description": "<p>Описание товара</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"products\": {\n    \"id\": 2,\n    \"vendoe_code\": \"Artikul\",\n    \"name\": \"Thing\",\n    \"model\": \"XX-01\",\n    \"price\": 50,\n    \"picture\": null,\n    \"picture_url\": null,\n    \"position\": 0,\n    \"category\": {\n      \"id\": 2,\n      \"name\": \"Dogs\"\n    },\n    \"sizes\": [],\n    \"description\": null\n  }\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Не указан параметр",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не указан обязательный параметр product_id\"\n}",
          "type": "json"
        },
        {
          "title": "Указана не существующий товар",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найдена товар с ID 123\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/ProductController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/product/list",
    "title": "6. Список всех товаров",
    "name": "Products_list",
    "group": "Shop",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "category_id",
            "description": "<p>ID категории (Необязательный параметр)</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"category_id\": 2\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "products",
            "description": "<p>Товары</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product.id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.vendor_code",
            "description": "<p>Артикул</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.name",
            "description": "<p>Название товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.model",
            "description": "<p>Модель</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "product.price",
            "description": "<p>Цена товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.picture",
            "description": "<p>Изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.picture_url",
            "description": "<p>Ссылка на изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product.position",
            "description": "<p>Позиция товара в категории</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "product.category",
            "description": "<p>Категория товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "product.category.id",
            "description": "<p>ID категории</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.category.name",
            "description": "<p>Название категории</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "product.sizes",
            "description": "<p>Размеры товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "product.description",
            "description": "<p>Описание товара</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"products\": [\n    {\n      \"id\": 1,\n      \"vendoe_code\": \"Artikul\",\n      \"name\": \"Product\",\n      \"model\": null,\n      \"price\": 25,\n      \"picture\": null,\n      \"picture_url\": null,\n      \"position\": 0,\n      \"category\": {\n        \"id\": 1,\n        \"name\": \"Cats\"\n      },\n      \"sizes\": [],\n      \"description\": null\n    },\n    {...}\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Указана не существующая категория",
          "content": "HTTP/1.1 400 BAD REQUEST\n{\n  \"result\": \"FAIL\",\n  \"error\": \"Не найдена категория с ID 123\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/ProductController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/shop/api-v3/order/list",
    "title": "3. Все заказы пользователя",
    "name": "User_orders_list",
    "group": "Shop",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "order_id",
            "description": "<p>ID заказа в системе</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "external_order_id",
            "description": "<p>Внешний ID заказа в</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"order_id\": 4\n}",
          "type": "json"
        },
        {
          "title": "Или:",
          "content": "{\n  \"external_order_id\": \"3012\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "orders",
            "description": "<p>Заказ</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.id",
            "description": "<p>ID Заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_order_id",
            "description": "<p>Внешний ID заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.external_user_id",
            "description": "<p>Внешний ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.profile_id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.status",
            "description": "<p>Статус заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.phone_mobile",
            "description": "<p>Мобильный телефон участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.email",
            "description": "<p>E-mail участника</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.delivery_address",
            "description": "<p>Адрес доставки</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.items_cost",
            "description": "<p>Сумма заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.delivery_cost",
            "description": "<p>Сумма доставки</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "order.total_cost",
            "description": "<p>Итоговая сумма</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.comment",
            "description": "<p>Комментарий к заказу</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "order.created",
            "description": "<p>Время создания заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "order.items",
            "description": "<p>Товары заказа</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.id",
            "description": "<p>ID позиции заказов</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product_id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.size",
            "description": "<p>Размер товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "item.price",
            "description": "<p>Цена</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.qty",
            "description": "<p>Количество</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item.product",
            "description": "<p>Товар</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.id",
            "description": "<p>ID товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.name",
            "description": "<p>Название товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Decimal",
            "optional": false,
            "field": "item.product.price",
            "description": "<p>Цена товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.picture",
            "description": "<p>Изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.picture_url",
            "description": "<p>Ссылка на изображение товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.position",
            "description": "<p>Позиция товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Object",
            "optional": false,
            "field": "item.product.category",
            "description": "<p>Категория товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Number",
            "optional": false,
            "field": "item.product.category.id",
            "description": "<p>ID категории товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.category.name",
            "description": "<p>Название категории товара</p>"
          },
          {
            "group": "Success 200",
            "type": "Array",
            "optional": false,
            "field": "item.product.sizes",
            "description": "<p>Размеры товара</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "item.product.description",
            "description": "<p>Описание товара</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример успешного ответа",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"orders\": [\n    {\n      \"order_id\": 4,\n      \"external_order_id\": \"3012\",\n      \"external_user_id\": \"3321\",\n      \"profile_id\": null,\n      \"status\": \"new\",\n      \"phone_mobile\": \"+79151913532\",\n      \"email\": \"nd@msforyou.ru\",\n      \"delivery_address\": \"Адрес доставки\",\n      \"items_cost\": 125,\n      \"delivery_cost\": 0,\n      \"total_cost\": 125,\n      \"comment\": null,\n      \"created\": \"23.04.2019 18:38\",\n      \"items\": [\n        {\n          \"id\": 5,\n          \"product_id\": 1,\n          \"size\": \"S\",\n          \"price\": 25,\n          \"qty\": 1,\n          \"product\": {\n          \"id\": 1,\n            \"name\": \"Product\",\n            \"price\": 25,\n            \"picture\": null,\n            \"picture_url\": null,\n            \"position\": 0,\n            \"category\": {\n              \"id\": 1,\n              \"name\": \"Cats\"\n            },\n            \"sizes\": [],\n            \"description\": null\n          }\n        },\n        {\n          \"id\": 6,\n          \"product_id\": 2,\n          \"size\": null,\n          \"price\": 50,\n          \"qty\": 2,\n          \"product\": {\n            \"id\": 2,\n            \"name\": \"Thing\",\n            \"price\": 50,\n            \"picture\": null,\n            \"picture_url\": null,\n            \"position\": 0,\n            \"category\": {\n              \"id\": 2,\n              \"name\": \"Dogs\"\n            },\n            \"sizes\": [],\n            \"description\": null\n          }\n        }\n    ]\n    },\n    {...}\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-shop/frontend/api/v3/controllers/OrderController.php",
    "groupTitle": "Shop"
  },
  {
    "type": "post",
    "url": "/taxes/api/ndfl/ndfl-info",
    "title": "Получить анкету НДФЛ участника",
    "name": "NdflInfo",
    "group": "Taxes",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_id\": 555111\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"form\": {\n    \"id\": 1028,\n    \"first_name\": \"Иван\",\n    \"last_name\": \"Иванов\",\n    \"middle_name\": \"Иванович\",\n    \"citizenship_id\": 185,\n    \"birthday_on\": \"1983-03-20\",\n    \"birthday_on_local\": \"20.03.1983\",\n    \"inn\": \"245405406810\",\n    \"address\": \"105082, г Москва, пер Спартаковский, д. 26, кв. 42\",\n    \"document_series_and_number\": \"0404 764601\",\n    \"document1\": {\n      \"filename\": \"document_5cff40690029d.jpeg\",\n      \"webpath\": \"http://penoplex-back.f/taxes/documents/get?account=1028&document=document_5cff40690029d.jpeg\",\n      \"src\": \"\",\n      \"url\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n    },\n    \"document2\": {\n      \"filename\": \"document2_5cff4069006f7.jpeg\",\n      \"webpath\": \"http://penoplex-back.f/taxes/documents/get?account=1028&document=document2_5cff4069006f7.jpeg\",\n      \"src\": \"\",\n      \"url\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n    },\n    \"document1_api\": {\n      \"file\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n    },\n    \"document2_api\": {\n      \"file\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n    },\n    \"status\": \"new\",\n    \"status_label\": \"Новая анкета\",\n    \"status_html\": \"<span class=\\\"label label-default\\\">Новая анкета</span>\",\n    \"comments\": [\n      {\n        \"id\": 4087,\n        \"created_at\": \"11.06.2019 08:47\",\n        \"status_old\": \"new\",\n        \"status_new\": \"new\",\n        \"comment\": null,\n        \"note\": \"Участник отправил анкету НДФЛ\",\n        \"admin_id\": null\n      },\n      {\n        \"id\": 4086,\n        \"created_at\": \"11.06.2019 08:38\",\n        \"status_old\": \"new\",\n        \"status_new\": \"new\",\n        \"comment\": null,\n        \"note\": \"Участник отправил анкету НДФЛ\",\n        \"admin_id\": null\n      },\n      {\n        \"id\": 4085,\n        \"created_at\": \"11.06.2019 08:37\",\n        \"status_old\": \"new\",\n        \"status_new\": \"new\",\n        \"comment\": null,\n        \"note\": \"Участник отправил анкету НДФЛ\",\n        \"admin_id\": null\n      }\n    ]\n  }\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-taxes/frontend/controllers/api/NdflController.php",
    "groupTitle": "Taxes"
  },
  {
    "type": "post",
    "url": "/taxes/api/ndfl/list",
    "title": "Список НДФЛ анкет",
    "name": "NdflList",
    "group": "Taxes",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "updated_from",
            "description": "<p>Дата обновления анкеты, что бы синхронизировать лишь новые данные</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"updated_from\": \"2019-06-08 23:31:05\"\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\",\n  \"accountProfiles\": [\n    {\n      \"account\": {\n        \"id\": 1014,\n        \"profile_id\": 13385,\n        \"name\": \"#13385 Иванов Иван Сергеевич\",\n        \"created_at\": \"2019-06-03 12:28:50\",\n        \"updated_at\": \"2019-06-03 12:28:50\"\n      },\n      \"account_id\": 1014,\n      \"first_name\": \"Иван\",\n      \"last_name\": \"Иванов\",\n      \"middle_name\": \"Сергеевич\",\n      \"birthday_on\": \"1984-08-24\",\n      \"inn\": \"682198917200\",\n      \"document_series_and_number\": \"6800 363120\",\n      \"address\": \"393760, Тамбовская обл, г Мичуринск, ул Карла Маркса, д. 12А, кв. 15\",\n      \"is_passport_num\": 1,\n      \"is_inn_num\": 1,\n      \"is_date_born\": 1,\n      \"is_fulfilled\": 1,\n      \"is_valid\": 0,\n      \"is_active\": 1,\n      \"address_is_actual\": 1,\n      \"status\": \"new\",\n      \"created_at\": \"2019-06-03 12:28:50\",\n      \"updated_at\": \"2019-06-09 20:22:20\",\n      \"validated_at\": \"2019-06-04 09:00:53\",\n      \"taxesHistory\": [\n        {\n          \"id\": 4084,\n          \"created_at\": \"09.06.2019 20:22\",\n          \"status_old\": \"new\",\n          \"status_new\": \"new\",\n          \"comment\": null,\n          \"note\": \"Участник отправил анкету НДФЛ\",\n          \"admin_id\": null\n        },\n        {\n          \"id\": 4039,\n          \"created_at\": \"04.06.2019 09:00\",\n          \"status_old\": \"new\",\n          \"status_new\": \"redo\",\n          \"comment\": \"просьба указать адрес в анкете такой же,  как в паспорте\",\n          \"note\": \"Анкета возвращена на доработку\",\n          \"admin_id\": 1\n        },\n        {\n          \"id\": 4038,\n          \"created_at\": \"03.06.2019 12:29\",\n          \"status_old\": \"new\",\n          \"status_new\": \"new\",\n          \"comment\": null,\n          \"note\": \"Участник отправил анкету НДФЛ\",\n          \"admin_id\": null\n        }\n      ]\n    },\n    {\n      \"account\": {\n        \"id\": 1021,\n        \"profile_id\": 18420,\n        \"name\": \"#18420 Юсупов Ильдар Измаилович\",\n        \"created_at\": \"2019-06-07 05:59:02\",\n        \"updated_at\": \"2019-06-07 05:59:02\"\n      },\n      \"account_id\": 1021,\n      \"first_name\": \"Ильдар\",\n      \"last_name\": \"Юсупов\",\n      \"middle_name\": \"Измаилович\",\n      \"birthday_on\": \"1977-11-08\",\n      \"inn\": \"161900059300\",\n      \"document_series_and_number\": \"9200 845500\",\n      \"address\": \"422060, Респ Татарстан, пгт Богатые Сабы, ул Школьная, д. 43, кв. 19\",\n      \"is_passport_num\": 1,\n      \"is_inn_num\": 1,\n      \"is_date_born\": 1,\n      \"is_fulfilled\": 1,\n      \"is_valid\": 0,\n      \"is_active\": 1,\n      \"address_is_actual\": 1,\n      \"status\": \"new\",\n      \"created_at\": \"2019-06-07 05:59:02\",\n      \"updated_at\": \"2019-06-09 17:34:51\",\n      \"validated_at\": null,\n      \"taxesHistory\": [\n        {\n          \"id\": 4082,\n          \"created_at\": \"09.06.2019 17:34\",\n          \"status_old\": \"new\",\n          \"status_new\": \"new\",\n          \"comment\": null,\n          \"note\": \"Участник отправил анкету НДФЛ\",\n          \"admin_id\": null\n        },\n        {\n          \"id\": 4063,\n          \"created_at\": \"07.06.2019 07:11\",\n          \"status_old\": \"new\",\n          \"status_new\": \"new\",\n          \"comment\": null,\n          \"note\": \"Участник отправил анкету НДФЛ\",\n          \"admin_id\": null\n        }\n      ]\n    }\n  ]\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-taxes/frontend/controllers/api/NdflController.php",
    "groupTitle": "Taxes"
  },
  {
    "type": "post",
    "url": "/taxes/api/ndfl/save-passport",
    "title": "Сохранить анкету НДФЛ по участнику",
    "name": "SavePassport",
    "group": "Taxes",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_id",
            "description": "<p>Идентификатор участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "first_name",
            "description": "<p>Имя</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "last_name",
            "description": "<p>Фамилия</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "middle_name",
            "description": "<p>Отчество</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "birthday_on_local",
            "description": "<p>Дата рождения</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "inn",
            "description": "<p>ИНН номер</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>Полный адрес</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "document_series_and_number",
            "description": "<p>Серия и номер паспорта</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "document1_api",
            "description": "<p>Разворот паспорта</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "document1_api.url",
            "description": "<p>Картинка или PDF в base64 строке</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "document2_api",
            "description": "<p>Страница регистрации паспорта</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "document2_api.url",
            "description": "<p>Картинка или PDF в base64 строке</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_id\": 555111,\n  \"first_name\": \"Иван\",\n  \"last_name\": \"Иванов\",\n  \"middle_name\": \"Иванович\",\n  \"birthday_on_local\": \"20.03.1983\",\n  \"inn\": \"245405406810\",\n  \"address\": \"Москва, Спартаковский пер., д. 26, кв. 42\",\n  \"document_series_and_number\": \"0404 764601\",\n  \"document1_api\": {\n    \"url\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n  },\n  \"document2_api\": {\n    \"url\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-taxes/frontend/controllers/api/NdflController.php",
    "groupTitle": "Taxes"
  },
  {
    "type": "post",
    "url": "/taxes/api/ndfl/save-passport",
    "title": "Сохранить анкету НДФЛ по участнику ВНЕШНЕМУ",
    "name": "SavePassportExternal",
    "group": "Taxes",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "profile_model",
            "description": "<p>Массив с данными внешнего участника</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "profile_model.id",
            "description": "<p>ID участника</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.first_name",
            "description": "<p>Имя</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.last_name",
            "description": "<p>Фамилия</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.middle_name",
            "description": "<p>Отчество (если имеется)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.phone_mobile_local",
            "description": "<p>Номер телефона</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "profile_model.email",
            "description": "<p>E-mail адрес (если имеется)</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "first_name",
            "description": "<p>Имя</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "last_name",
            "description": "<p>Фамилия</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "middle_name",
            "description": "<p>Отчество</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "birthday_on_local",
            "description": "<p>Дата рождения</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": true,
            "field": "inn",
            "description": "<p>ИНН номер</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "address",
            "description": "<p>Полный адрес</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "document_series_and_number",
            "description": "<p>Серия и номер паспорта</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "document1_api",
            "description": "<p>Разворот паспорта</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "document1_api.url",
            "description": "<p>Картинка или PDF в base64 строке</p>"
          },
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "document2_api",
            "description": "<p>Страница регистрации паспорта</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "document2_api.url",
            "description": "<p>Картинка или PDF в base64 строке</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Пример запроса:",
          "content": "{\n  \"profile_model\": {\n    \"id\": 555111,\n    \"first_name\": \"Иван\",\n    \"last_name\": \"Иванов\",\n    \"middle_name\": \"Иванович\",\n    \"phone_mobile_local\": \"+79299004010\",\n    \"email\": \"ivanov-chel@mail.ru\"\n  },\n  \"first_name\": \"Иван\",\n  \"last_name\": \"Иванов\",\n  \"middle_name\": \"Иванович\",\n  \"birthday_on_local\": \"20.03.1983\",\n  \"inn\": \"245405406810\",\n  \"address\": \"Москва, Спартаковский пер., д. 26, кв. 42\",\n  \"document_series_and_number\": \"0404 764601\",\n  \"document1_api\": {\n    \"url\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n  },\n  \"document2_api\": {\n    \"url\": \"data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wgARCAACAAIDAREAAhEBAxEB/8QAFAABAAAAAAAAAAAAAAAAAAAACP/EABQBAQAAAAAAAAAAAAAAAAAAAAD/2gAMAwEAAhADEAAAAVSf/8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABBQJ//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPwF//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPwF//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAGPwJ//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPyF//9oADAMBAAIAAwAAABCf/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAwEBPxB//8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAgBAgEBPxB//8QAFBABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQABPxB//9k=\"\n  }\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "examples": [
        {
          "title": "Пример успешного ответа:",
          "content": "HTTP/1.1 200 OK\n{\n  \"result\": \"OK\"\n}",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "app/vendor/marketingsolutions/loyalty-taxes/frontend/controllers/api/NdflController.php",
    "groupTitle": "Taxes"
  }
] });

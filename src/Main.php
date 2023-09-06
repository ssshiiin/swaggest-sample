<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/JsonSchema.php';

$schema = (new JsonSchema)->getNodeInlineSchema();
$result = $schema->in(json_decode(<<<JSON
[
  {
    "type": "bold",
    "children": [
      {
        "text": "bold > text"
      }
    ]
  },
  {
    "text": "text"
  },
  {
    "type": "italic",
    "children": [
      {
        "text": "italic > text"
      },
      {
        "type": "ref",
        "id": "10204830",
        "children": [
          {
            "text": "italic > ref > text"
          }
        ]
      }
    ]
  }
]
JSON));

var_dump($result);
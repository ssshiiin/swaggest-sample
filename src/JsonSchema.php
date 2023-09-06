<?php

use Swaggest\JsonSchema\Context;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\SchemaContract;
use Swaggest\JsonSchema\RemoteRef\Preloaded;

final class JsonSchema
{
    const NODE_SCHEMA_DIR = __DIR__ . '/schemas/node/';
    const NODE_SCHEMA_URI =  "http://localhost:1234/schemas/node/";
    const NODE_INLINE_SCHEMA_FILE = 'inline.json';
    const NODE_BOLD_SCHEMA_FILE = 'bold.json';
    const NODE_ITALIC_SCHEMA_FILE = 'italic.json';
    const NODE_REF_SCHEMA_FILE = 'ref.json';
    const NODE_TEXT_SCHEMA_FILE = 'text.json';

    /**
     * JSON Schema (node inline)
     *
     * @return SchemaContract
     */
    public function getNodeInlineSchema(): SchemaContract
    {
        $options = new Context();
        $options->setRemoteRefProvider($this->createNodeJsonSchemaRemoteRefPreloaded([
            self::NODE_BOLD_SCHEMA_FILE,
            self::NODE_ITALIC_SCHEMA_FILE,
            self::NODE_REF_SCHEMA_FILE,
            self::NODE_TEXT_SCHEMA_FILE,
        ]));
        return Schema::import($this->loadNodeSchemaFileAndJsonDecode(self::NODE_INLINE_SCHEMA_FILE), $options);
    }

    private function loadNodeSchemaFileAndJsonDecode(string $filename): stdClass
    {
        $filepath = self::NODE_SCHEMA_DIR . $filename;
        $decoded = json_decode(file_get_contents($filepath));
        if ($decoded instanceof stdClass) {
            return $decoded;
        }

        throw new RuntimeException("failed to decode json. {$filepath}");
    }

    private function createNodeJsonSchemaRemoteRefPreloaded(array $filenames): Preloaded
    {
        $refProvider = new Preloaded();
        foreach ($filenames as $f) {
            $name = basename($f, '.json');
            $refProvider->setSchemaData(
                self::NODE_SCHEMA_URI . $name,
                self::loadNodeSchemaFileAndJsonDecode($f)
            );
        }

        return $refProvider;
    }
}
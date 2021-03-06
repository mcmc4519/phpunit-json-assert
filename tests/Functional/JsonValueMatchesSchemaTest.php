<?php
namespace Helmich\JsonAssert\Tests\Functional;

use Helmich\JsonAssert\JsonAssertions;
use PHPUnit\Framework\TestCase;

class JsonValueMatchesSchemaTest extends TestCase
{
    use JsonAssertions;

    private static $exampleDocument = [
        'identifier' => '1234',
        'owner' => [
            'identifier' => '4321',
            'name' => 'Max Mustermann',
        ],
        'products' => [
            [
                'identifier' => 500,
                'name' => 'Roggenbrot',
                'category' => 'Brot',
            ],
            [
                'identifier' => 501,
                'name' => 'Graubrot',
                'category' => 'Brot',
            ],
        ],
    ];

    public function testJsonDocumentMatchesSchema()
    {
        $this->assertJsonDocumentMatchesSchema(static::$exampleDocument, [
            'type'       => 'object',
            'required'   => ['identifier', 'owner', 'products'],
            'properties' => [
                'identifier' => [
                    'type' => 'string'
                ],
                'owner'      => [
                    'type'       => 'object',
                    'properties' => [
                        'identifier' => ['type' => 'string'],
                        'name'       => ['type' => 'string'],
                    ]
                ],
                'products'   => [
                    'type'  => 'array',
                    'items' => [
                        'type'       => 'object',
                        'properties' => [
                            'identifier' => ['type' => 'number'],
                            'name'       => ['type' => 'string'],
                            'category'   => ['type' => 'string'],
                        ]
                    ]
                ]
            ]
        ]);
    }

    /**
     * @expectedException \PHPUnit\Framework\AssertionFailedError
     */
    public function testJsonDocumentDoesNotMatchSchema()
    {
        $this->assertJsonDocumentMatchesSchema(static::$exampleDocument, [
            'type' => 'object',
            'required' => ['foobar'],
            'properties' => [
                'foobar' => ['type' => 'string']
            ]
        ]);
    }

    public function testStringsAreInterpretedAsJsonString()
    {
        $this->assertJsonDocumentMatchesSchema('{"foo": 123}', [
            'type'     => 'object',
            'required' => ['foo'],
            'properties' => [
                'foo' => ['type' => 'number']
            ]
        ]);
    }
}
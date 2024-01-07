<?php

namespace Unicon\Unicon;

use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocChildNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\ConstExprParser;
use PHPStan\PhpDocParser\Parser\PhpDocParser as PHPStanPhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;
use PHPStan\PhpDocParser\Parser\TypeParser as PHPStanTypeParser;
use PHPStan\PhpDocParser\Printer\Printer;

class PhpDocParser
{
    private static Lexer $lexer;
    private static PHPStanTypeParser $typeParser;
    private static PHPStanPhpDocParser $docParser;

    private static Printer $printer;

    /**
     * @param string $phpDoc
     * @return array<PhpDocChildNode>
     */
    public static function parse(string $phpDoc): array
    {
        self::initLexer();
        self::initPhpDocParser();
        $topNode = self::$docParser->parse(
            new TokenIterator(
                self::$lexer->tokenize($phpDoc)
            )
        );
        return $topNode->children;
    }

    public static function parseType(string $type): TypeNode
    {
        self::initLexer();
        self::initTypeParser();
        return self::$typeParser->parse(
            new TokenIterator(
                self::$lexer->tokenize($type)
            )
        );
    }

    /**
     * @param string $phpDoc
     * @return array<string,TypeNode>
     */
    public static function parseParams(string $phpDoc): array
    {
        $ret = [];
        $rows = self::parse($phpDoc);
        foreach ($rows as $row) {
            if ($row instanceof PhpDocTagNode && $row->value instanceof ParamTagValueNode) {
                $ret[self::stripDollar($row->value->parameterName)] = $row->value->type;
            }
        }
        return $ret;
    }

    public static function parseVar(string $phpDoc, string $parameterName): ?TypeNode
    {
        $ret = [];
        $rows = self::parse($phpDoc);
        foreach ($rows as $row) {
            if (
                $row instanceof PhpDocTagNode
                && $row->value instanceof VarTagValueNode
            ) {
                $varName = self::stripDollar($row->value->variableName);
                if ($varName == '' || $varName == $parameterName) {
                    return $row->value->type;
                }
            }
        }
        return null;
    }

    public static function printType(TypeNode $typeNode): string
    {
        self::initPrinter();
        return self::$printer->print($typeNode);
    }

    private static function initLexer(): void
    {
        if (!isset(self::$lexer)) {
            self::$lexer = new Lexer();
        }
    }

    private static function initTypeParser(): void
    {
        if (!isset(self::$typeParser)) {
            self::$typeParser = new PHPStanTypeParser(new ConstExprParser());
        }
    }

    private static function initPhpDocParser(): void
    {
        self::initTypeParser();
        if (!isset(self::$docParser)) {
            self::$docParser = new PHPStanPhpDocParser(
                self::$typeParser,
                new ConstExprParser()
            );
        }
    }

    private static function initPrinter(): void
    {
        if (!isset(self::$printer)) {
            self::$printer = new Printer();
        }
    }

    private static function stripDollar(string $source): string
    {
        return str_starts_with($source, '$') ? substr($source, 1) : $source;
    }
}

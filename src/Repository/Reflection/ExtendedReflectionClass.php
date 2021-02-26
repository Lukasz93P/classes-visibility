<?php

declare(strict_types=1);

namespace Lukasz93P\ClassVisibility\Repository\Reflection;

use ReflectionClass;

class ExtendedReflectionClass extends ReflectionClass
{
    public function getUseStatements(): array
    {
        $content = token_get_all(file_get_contents($this->getFileName()));
        $content = $this->removeTokens(T_COMMENT, $content);
        $content = $this->removeTokens(T_WHITESPACE, $content);

        $tokenUses = [];
        $level = 0;

        foreach ($content as $key => $token) {
            if (is_string($token)) {
                if ($token === '{') {
                    $level++;
                }
                if ($token === '}') {
                    $level--;
                }
            }
            if (!is_string($token) && $token[0] === T_USE && $level === 0) {
                $tokenUses[] = $key;
            }
        }

        $useStatements = [];

        foreach ($tokenUses as $key => $tokenKey) {
            $i = $tokenKey;
            $char = '';
            $useStatements[$key] = '';

            while ($char !== ';') {
                ++$i;
                $char = is_string($content[$i]) ? $content[$i] : $content[$i][1];

                if (!is_string($content[$i]) && $content[$i][0] === T_AS) {
                    break;
                }

                $useStatements[$key] .= $char;

                if ($char === '(') {
                    unset($useStatements[$key]);
                    break;
                }
            }
        }

        $allUses = [];

        foreach ($useStatements as $fullStmt) {
            $fullStmt = rtrim($fullStmt, ';');
            //$fullStmt = preg_replace('/^.+ AS /', '', $fullStmt);
            $fullStmt = explode(',', $fullStmt);
//            foreach ($fullStmt as $statement) {
//                $asStatementPosition = strpos($statement)
//            }
            $allUses[] = $fullStmt;
        }

        return array_merge([], ...$allUses);
    }

    private function removeTokens($tokenId, array $content): array
    {
        foreach ($content as $key => $token) {
            if (isset($token[0]) && $token[0] === $tokenId) {
                unset($content[$key]);
            }
        }

        return array_values($content);
    }
}
<?php

/*
 * This file is part of the PHP CS utility.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Symfony\CS\Fixer\Drupal;

use Symfony\CS\AbstractFixer;
use Symfony\CS\Tokenizer\Tokens;

/**
 * Fixer for rules defined in Drupal coding standards.
 *
 * @author Vijayachandran Mani <vijaycs85@gmail.com>
 */
class IndentationFixer extends AbstractFixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(\SplFileInfo $file, $content)
    {
        $tokens = Tokens::fromCode($content);

        foreach ($tokens as $index => $token) {
            if ($token->isComment()) {
                $content = preg_replace('/^(?:(?<! ) {1,3})?\t/m', '\1  ', $token->getContent(), -1, $count);

                // Also check for more tabs.
                while ($count !== 0) {
                    $content = preg_replace('/^(\ +)?\t/m', '\1  ', $content, -1, $count);
                }

                $tokens[$index]->setContent($content);
                continue;
            }

            if ($token->isWhitespace()) {
                $tokens[$index]->setContent(preg_replace('/(?:(?<! ) {1,3})?\t/', '  ', $token->getContent()));
            }
        }

        return $tokens->generateCode();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return 'Code MUST use an indent of 2 spaces, and MUST NOT use tabs for indenting.';
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 50;
    }
}

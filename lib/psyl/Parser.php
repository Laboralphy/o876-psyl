<?php

namespace O876\Psyl;

require_once 'Exception.php';
require_once 'Atom.php';

use O876\Psyl\Exception;

/**
 * @brief a PSYL parser
 *
 * Psyl is a simplified variant of lisp.
 * A psyl source contains lists and atoms
 * a list is a collection of lists or atoms delimited by "space" character, begining with "(" and ending with ")"
 * ex: (this is a list of strings) ("this" "is" "a" "list" "of" "strings")
 * (nested lists (nested list 1) (nested list 2))
 *
 * The parse will turn a psyl source into a structure of nested arrays
 *
 * @author Raphaël Marandet
 * @version 100 - 2012.07.02
 */

class Parser {

    const TOKEN_OP = '(';
    const TOKEN_CL = ')';
    const TOKEN_QU = '"';
    const TOKEN_ESC = '\\';

    protected $sData;
    protected $nIndex;
    protected $nDataLen;
    protected $nCurse;

    /**
     * Returns true if the parser reaches end of source.
     * @return boolean
     */
    protected function eod() {
        return $this->nIndex >= $this->nDataLen;
    }

    /**
     * advance character index by one  position (or more)
     * @param $n int number of character
     * @return int current index
     */
    protected function advance($n = 1) {
        return $this->nIndex += $n;
    }

    /**
     * returns true if the current character is whitespace (non-printable char)
     * @return boolean
     */
    protected function peekIsWhiteChar() {
        return trim($this->peek()) == '';
    }

    /**
     * returns true if the current character is a string enclosing character (usually " double-quote)
     * @return boolean
     */
    protected function peekIsQuote() {
        return $this->peek() == static::TOKEN_QU;
    }

    /**
     * returns true if the current character is an escape code (usually \)
     * @return boolean
     */
    protected function peekIsEscape() {
        return $this->peek() == static::TOKEN_ESC;
    }

    /**
     * advance source input reading until printable character is found
     */
    protected function advanceNextChar() {
        while (!$this->eod() && $this->peekIsWhiteChar()) {
            $this->advance();
        }
    }

    /**
     * read the next character without advancing index
     * @return string
     */
    protected function peek() {
        return $this->sData[$this->nIndex];
    }

    /**
     * returns the depth value
     * @return integer
     */
    public function getCurse() {
        return $this->nCurse;
    }

    /**
     * Parses a string and build an array
     * @param $sData string
     * @return array
     */
    public function parse($sData) {
        $this->sData = $sData;
        $this->nIndex = 0;
        $this->nDataLen = strlen($this->sData);
        while (!$this->eod()) {
            switch ($this->peek()) {
                case self::TOKEN_OP: // début new list
                    $this->nCurse = 0;
                    return $this->parseList();
                    break;
            }
            $this->advance();
        }
        return array();
    }

    /**
     * Parses a list
     * @return array
     * @throws
     */
    protected function parseList() {
        ++$this->nCurse;
        $this->advance();
        $this->advanceNextChar();
        $oList = array();
        while (!$this->eod()) {
            switch ($this->peek()) {
                case self::TOKEN_OP: // liste imbriquée
                    $oList[] = $this->parseList();
                    $this->advance();
                    break;

                case self::TOKEN_CL: // liste nulle
                    $this->nCurse--;
                    return $oList;
                    break;

                default: // donnée dans la liste
                    $oList[] = $this->parseAtom();
                    break;
            }
            $this->advanceNextChar();
        }
        throw new Exception('parse: unexpected end of data');
    }

    /**
     * Parses an atom
     * @return string
     * @throws
     */
    protected function parseAtom() {
        $this->nCurse++;
        $sWord = '';
        if ($this->peekIsQuote()) {
            $bQuoted = true;
            $this->advance();
        } else {
            $bQuoted = false;
        }
        while (!$this->eod()) {
            $bEscape = false;
            if ($this->peekIsEscape()) {
                $bEscape = true;
                $this->advance();
            }
            if (!$bEscape && $this->peekIsQuote() && $bQuoted) {
                $this->advance();
                break;
            }
            if (!$bEscape && !$bQuoted && ($this->peekIsWhiteChar() || $this->peek() == self::TOKEN_CL || $this->peek() == self::TOKEN_OP)) {
                break;
            }
            $sWord .= $this->peek();
            $this->advance();
        }
        --$this->nCurse;
        $node = new Atom();
        return $node->value($sWord)->index($this->nIndex - strlen($sWord));
    }
}


<?php

namespace common\components\csvimport;

interface ICSVRow
{
    function getEmail();
    function getName();
    function getAmount();
    function getComment();
    function getTimestamp();
}
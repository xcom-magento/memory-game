<?php

namespace Xcom\MemoryGame\Block;

class MemoryGame extends \Magento\Framework\View\Element\Template
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context)
    {
        parent::__construct($context);
    }

    public function getMemoryGame()
    {
        return __('MemoryGame');
    }
}

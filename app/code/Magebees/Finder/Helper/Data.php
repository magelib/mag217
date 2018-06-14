<?php
namespace Magebees\Finder\Helper;

use Magento\Store\Model\ScopeInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
  
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->request = $request;
        parent::__construct($context);
    }
    
    public function getFinderId($path)
    {
        $finderparams = [];
        $path = trim($path, '/');
        $finderparams = explode('/', $path);
        if (array_key_exists(1, $finderparams)) {
            $finderId = $finderparams[1];
        } else {
            return 0;
        }
        return $finderId;
    }
    
    public function resetFinder($path)
    {
        $finderparams = [];
        $path = trim($path, '/');
        $finderparams = explode('/', $path);
        if (array_key_exists('2', $finderparams)) {
            return true;
        } else {
            return false;
        }
    }
}

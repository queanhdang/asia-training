<?php
 
namespace AHT\AddAttribute\Model\Config\Source;
 
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
 
 
class MyMultiselectOptions extends AbstractSource
{
    protected $optionFactory;
    public function getAllOptions()
    {
        $this->_options = [];
        $this->_options[] = ['label' => 'Label 1', 'value' => 'value 1'];
        $this->_options[] = ['label' => 'Label 2', 'value' => 'value 2'];
        $this->_options[] = ['label' => 'Label 3', 'value' => 'value 3'];
        $this->_options[] = ['label' => 'Label 4', 'value' => 'value 4'];
    
        return $this->_options;
    }
}
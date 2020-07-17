<?php
namespace App\Model;

use Illuminate\Support\ViewErrorBag;

/**
 * Helpers for building laravelcollective/html form components
 *
 * @author G Brabyn
 */
class FormFieldHelper 
{
    /** @var string */
    private $fieldName;
    
    /** @var ViewErrorBag */
    private $errors;
    
    /** @var array */
    private $attributes;
    
    /** @var string */
    private $errorName;
    
    public function __construct(string $fieldName, ViewErrorBag $errors, array $attributes)
    {
        $this->fieldName = $fieldName;
        $this->errors = $errors;
        $this->attributes = $attributes;
        $this->errorName = $this->makeErrorName($fieldName);
    }
    
    private function makeErrorName(string $fieldName) : string
    {
        return \str_replace(['[', '"', '\'', ']'], ['.'], $fieldName);
    }
    
    public function getErrorName() : string
    {
        return $this->errorName;
    }
    
    public function getAttributes() : array
    {
        $attributes = $this->attributes;
        
        if($this->errors->has($this->errorName)){
            if(\array_key_exists('class', $attributes)){
                $attributes['class'] .= ' error';
            }else{
                $attributes['class'] = 'error';
            }
        }
        
        return $attributes;
    }
    
    public function getAttributesString() : string
    {
        $attr = [];
        foreach($this->getAttributes() as $k => $v){
            if(! is_int($k)){
                $attr[] = e($k).'="'.e($v).'"';
            }else{
                $attr[] = e($v);
            }
        }
        
        return \implode(' ', $attr);
    }
    
    /**
     * Removes '[]' from end of $fieldName if exists
     * 
     * @param string $fieldName
     * @return string
     */
    public static function removeArrayEndingFromFieldName(string $fieldName) : string
    {
        return \preg_replace('/'. \preg_quote('[]', '/') . '$/', '', $fieldName);
    }
    
}

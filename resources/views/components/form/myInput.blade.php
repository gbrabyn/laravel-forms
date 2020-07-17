@php
    $helper = new \App\Model\FormFieldHelper($name, $errors, $attributes);
@endphp

{{ Form::input($type, $name, $value, $helper->getAttributes()) }}
@error($helper->getErrorName())
<br><span class="error msgBelow">{{ $message }}</span>
@enderror

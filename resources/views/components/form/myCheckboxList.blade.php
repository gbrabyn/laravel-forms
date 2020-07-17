@php
    $_name = \App\Model\FormFieldHelper::removeArrayEndingFromFieldName($name);
    $helper = new \App\Model\FormFieldHelper($_name, $errors, $attributes);
@endphp
@error($helper->getErrorName())
<span class="error msgAbove">{{ $message }}</span><br>
@enderror
@error($helper->getErrorName().'.*')
<span class="error msgAbove">{{ $message }}</span><br>
@enderror
<ul {!! $helper->getAttributesString() !!}>
@foreach ($options as $v => $label)
    <li>
        <label>
            {!! Form::checkbox($_name.'[]', $v) !!}
            {{ $label }}
        </label>
    </li>
@endforeach
</ul>
@php
    $helper = new \App\Model\FormFieldHelper($name, $errors, $attributes);
@endphp
@error($helper->getErrorName())
<span class="error msgAbove">{{ $message }}</span><br>
@enderror
<ul {!! $helper->getAttributesString() !!}>
@foreach ($options as $v => $label)
    <li>
        <label>
            {!! Form::radio($name, $v) !!}
            {{ $label }}
        </label>
    </li>
@endforeach
</ul>
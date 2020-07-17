@php
    /** @var $form App\Forms\ViewWrapper\ViewWrapper
     *  @var $fieldKey string
     *  @var $placeholder string
     *  @var $nameAttribute string
     */
@endphp
<tr>
    <td>
        {!! $form->text($fieldKey, ['class'=>'medium', 'placeholder'=>$placeholder, 'name'=>$nameAttribute], 'below') !!}
    </td>
    <td>
        <a href="#" class="removeTag">@lang('messages.Remove')</a>
    </td>
</tr>

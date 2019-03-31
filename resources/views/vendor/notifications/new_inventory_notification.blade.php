@component('mail::message')

# Hello!
{{$data['description']}} 

<table class="table" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th align="center">
            Name
        </th>
        <th align="center">
            Access Card Number
        </th>
        <th align="center">
            Status
        </th>
    </tr>
    <tr>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['access_card_name']}}
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{$data['access_card_no']}}
            
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;color:{{$data['status_color']}}" >
            <b> {{$data['status_akses']}} </b>
        </td>
    </tr>
</table>

@component('mail::button', ['url'=>$data['url_data']])
Direct to Data
@endcomponent

@if (! empty($data['note']))
    {{$data['note']}}
@endif


Thank you for using our application!

Thanks,<br>
{{ config('app.name') }}

{{-- Subcopy --}}
@component('mail::subcopy')
If you’re having trouble clicking the "{{ $data['url'] }}" button, copy and paste the URL below
into your web browser: [{{ $data['url'] }}]({{ $data['url'] }})
@endcomponent

@endcomponent
@component('mail::message')

# Hi {{$data['to']}}

<b> {{$data['from']}} </b> {{$data['desc']}} dengan data sebagai berikut


<table class="table" align="center" width="100%" cellpadding="0" cellspacing="0">
    <tr>
        <th align="center">
            Nama Barang
        </th>
        <th align="center">
            Kategori
        </th>
        <th align="center">
            Jumlah
        </th>
    </tr>
    <tr>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['nama_barang']}}
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['kategori']}}
        </td>
        <td align="center" style="border-bottom: 1px solid #EDEFF2;">
            {{ $data['count']}}
            
        </td>
    </tr>
</table>

<br>
@component('mail::left_button', ['url'=>$data['url'],'url1' => $data['url1'],'url2'=>$data['url2']])
View Invoice
@endcomponent


<br>
<br>
Thanks,<br>
{{ config('app.name') }}

{{-- Subcopy --}}
@component('mail::subcopy')
If you’re having trouble clicking the "{{ $data['url'] }}" button, copy and paste the URL below
into your web browser: [{{ $data['url'] }}]({{ $data['url'] }})
@endcomponent

@endcomponent
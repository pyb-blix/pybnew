<div style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;margin-top:50px">

  <h2 style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-weight:bold;line-height:28px;padding-bottom:10px;font-size:26px;color:#565a5c">
    {{ trans('messages.email.price_details',[], null, $locale) }}
  </h2>
  <table style="display:none;margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%;font-size:0;line-height:0" width="100%" cellpadding="0" cellspacing="0">
    <tbody><tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
      <td style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:0" height="40">
        &nbsp;
      </td>
    </tr>
  </tbody></table>
  <table style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;line-height:150%;border-spacing:0;width:100%">
    <tbody>
      @if(@$result['duration'] != '')
      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ ucwords(trans_choice('experiences.manage.hour_s',2, [], null, $locale)) }}
          </p>
        </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{@$result['duration']}}
          </p>
        </td>
      </tr>
      @endif
      @if($result['number_of_guests'] > 0)
      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ html_entity_decode($result['currency']['symbol']) }}{{ $result['per_night'].' x '.$result['number_of_guests'] }} {{ trans_choice('experiences.payment.guest_s',$result['number_of_guests'] , [], null, $locale) }}
          </p>
        </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ html_entity_decode($result['currency']['symbol']) }}{{ $result['per_night']*$result['number_of_guests'] }}
          </p>
        </td>
      </tr>
      @endif

      @if($result['service'] > 0 && $to != 'host')
      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ trans('messages.your_reservations.service_fee', [], null, $locale) }}
          </p>
        </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ html_entity_decode($result['currency']['symbol']) }}{{$result['service']}}
          </p>
        </td>
      </tr>
      @endif
      @if($result['host_fee'] > 0 && $to != 'guest')
      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ trans('messages.your_reservations.service_fee', [], null, $locale) }}
          </p>
        </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ html_entity_decode($result['currency']['symbol']) }}{{$result['service']}}
          </p>
        </td>
      </tr>
      @endif

      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            @if($to == 'host')
              {{ trans('messages.your_reservations.total_payout', [], null, $locale) }}
            @else
              {{ trans('messages.your_reservations.subtotal', [], null, $locale) }}
            @endif
          </p>
        </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ html_entity_decode($result['currency']['symbol']) }}{{$result['subtotal']}}
          </p>
        </td>
      </tr>

      @if($to != 'host')
      <tr style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif">
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ ucfirst(trans('messages.your_reservations.total', [], null, $locale)) }}
          </p>
        </td>
        <td style="margin:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;padding:0;padding-left:30px" valign="top">
          <p style="margin:0;padding:0;font-family:&quot;Helvetica Neue&quot;,&quot;Helvetica&quot;,Helvetica,Arial,sans-serif;font-size:18px;line-height:32px;color:#565a5c">
            {{ html_entity_decode($result['currency']['symbol']) }} {{$result['total']}}
          </p>
        </td>
      </tr>
      @endif

    </tbody>
  </table>
</div>
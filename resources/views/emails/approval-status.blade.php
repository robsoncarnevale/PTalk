@extends('emails.template.material-design-bootstrap')

@section('title')
{{ $title }}
@endsection

@section('content')
  <!-- pre-header -->
  <table style="display:none!important;">
    <tr>
        <td>
            <div style="overflow:hidden;display:none;font-size:1px;color:#ffffff;line-height:1px;font-family:Arial;maxheight:0px;max-width:0px;opacity:0;">
              Bem vindo ao {{ $club->name }}!
            </div>
        </td>
    </tr>
  </table>
  <!-- pre-header end -->

  <!-- big image section -->
  <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">
    <tr>
        <td align="center">
            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                <tr>
                    <td height="20" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="left">
                        <table border="0" width="590" align="center" cellpadding="0" cellspacing="0" class="container590">
                            <tr>
                                <td align="left" style="color: #888888; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                    <!-- section text ======-->

                                    <p style="line-height: 24px; margin-bottom:15px;">
                                        <strong>{{ $user_name }}</strong>,
                                    </p>
                                    <p style="line-height: 24px;margin-bottom:15px;">
                                        Legal! Sua participação ao clube {{ $club->name }}. foi aprovada! 
                                    </p> 
                                    
                                    <p style="line-height: 24px; margin-bottom:20px;">
                                        Seja bem-vindo a nossa comunidade, acesse nosso App e fique por dentro das novidades!
                                    </p>

                                    @include('emails.sections.att')
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
  </table>
  <!-- end section -->

  <!-- contact section -->
  @include('emails.sections.contact')
  <!-- end section -->
@endsection
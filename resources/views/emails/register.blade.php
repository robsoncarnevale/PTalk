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
                    <td align="center" style="color: #343434; font-size: 24px; font-family: Quicksand, Calibri, sans-serif; font-weight:700;letter-spacing: 3px; line-height: 35px;"
                        class="main-header">
                        <!-- section text ======-->
                        <div style="line-height: 35px">
                          Bem vindo ao <span class="color">{{ $club->name }}</span>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="center">
                        <table border="0" width="40" align="center" cellpadding="0" cellspacing="0" bgcolor="eeeeee">
                            <tr>
                                <td height="2" style="font-size: 2px; line-height: 2px;">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>

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
                                        {{ $user_name }},
                                    </p>
                                    <p style="line-height: 24px;margin-bottom:15px;">
                                        Agora você pode acessar o painel do {{ $club->name }}. Caso não tenha efetuado o cadastro, por favor desconsidere.
                                    </p>
                                    <p style="line-height: 24px; margin-bottom:20px;">
                                        Efetue o acesso inicial a sua conta e crie uma senha utilizando o link abaixo.
                                    </p>
                                    <table border="0" align="center" width="180" cellpadding="0" cellspacing="0" style="margin-bottom:20px;" bgcolor="{{ $color }}">
                                        <tr>
                                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 22px; letter-spacing: 2px;">
                                                <!-- main section button -->
                                                <div style="line-height: 22px;">
                                                  <a href="" style="color: #ffffff; text-decoration: none;">{{ $club->name }}</a>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                                        </tr>

                                    </table>
                                    <p style="line-height: 24px">
                                        Atenciosamente,</br>
                                        Equipe {{ $club->name }}
                                    </p>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

        </td>
    </tr>

    <tr>
        <td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td>
    </tr>
  </table>
  <!-- end section -->


  <!-- main section -->
  {{-- <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="2a2e36">
    <tr>
        <td align="center" style="background-image: url(https://mdbootstrap.com/img/Photos/Others/slide.jpg); background-size: cover; background-position: top center; background-repeat: no-repeat;"
            background="https://mdbootstrap.com/img/Photos/Others/slide.jpg">

            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">
                <tr>
                    <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="center">
                        <table border="0" width="380" align="center" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                            class="container590">

                            <tr>
                                <td align="center">
                                    <table border="0" align="center" cellpadding="0" cellspacing="0" class="container580">
                                        <tr>
                                            <td align="center" style="color: #cccccc; font-size: 16px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 26px;">
                                                <!-- section text ======-->

                                                <div style="line-height: 26px">
                                                    The all new AW16 range is out. View an exclusive preview.
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

                <tr>
                    <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                </tr>

                <tr>
                    <td align="center">
                        <table border="0" align="center" width="250" cellpadding="0" cellspacing="0" style="border:2px solid #ffffff;">

                            <tr>
                                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                            </tr>

                            <tr>
                                <td align="center" style="color: #ffffff; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 22px; letter-spacing: 2px;">
                                    <!-- main section button -->

                                    <div style="line-height: 22px;">
                                        <a href="" style="color: #fff; text-decoration: none;">VIEW THE COLLECTION</a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td height="10" style="font-size: 10px; line-height: 10px;">&nbsp;</td>
                            </tr>

                        </table>
                    </td>
                </tr>


                <tr>
                    <td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td>
                </tr>

            </table>
        </td>
    </tr>
  </table> --}}
  <!-- end section -->

  <!-- contact section -->
  <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="ffffff" class="bg_color">
    <tr>
        <td height="60" style="font-size: 60px; line-height: 60px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">
            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590 bg_color">
                <tr>
                    <td align="center">
                        <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590 bg_color">

                            <tr>
                                <td>
                                    <table border="0" width="300" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                        class="container590">

                                        <tr>
                                            <!-- logo -->
                                            <td align="left">
                                                <a href="#" style="display: block; border-style: none !important; border: 0 !important;">
                                                  <img width="80" border="0" style="display: block; width: 80px;" src="{{ $logo_url }}" alt="" />
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td align="left" style="color: #888888; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 23px;"
                                                class="text_color">
                                                
                                                @if($club->contact_mail)
                                                <div style="color: #333333; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; font-weight: 600; mso-line-height-rule: exactly; line-height: 23px;">
                                                  Contato: <br/> <a href="mailto:" style="color: #888888; font-size: 14px; font-family: 'Hind Siliguri', Calibri, Sans-serif; font-weight: 400;">{{ $club->contact_mail }}</a>
                                                </div>
                                                @endif
                                            </td>
                                        </tr>

                                    </table>

                                    <table border="0" width="2" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                        class="container590">
                                        <tr>
                                            <td width="2" height="10" style="font-size: 10px; line-height: 10px;"></td>
                                        </tr>
                                    </table>

                                    <table border="0" width="200" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                        class="container590">

                                        <tr>
                                            <td class="hide" height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td>
                                        </tr>



                                        <tr>
                                            <td height="15" style="font-size: 15px; line-height: 15px;">&nbsp;</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <table border="0" align="right" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td>
                                                            <a href="https://www.facebook.com/porschetalk" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="http://i.imgur.com/Qc3zTxn.png" alt=""></a>
                                                        </td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td>
                                                          <a href="https://www.instagram.com/porschetalk" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="http://i.imgur.com/Qc3zTxn.png" alt=""></a>
                                                        </td>
                                                        {{-- <td>
                                                            <a href="https://twitter.com/MDBootstrap" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="http://i.imgur.com/RBRORq1.png" alt=""></a>
                                                        </td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                        <td>
                                                            <a href="https://plus.google.com/u/0/b/107863090883699620484/107863090883699620484/posts" style="display: block; border-style: none !important; border: 0 !important;"><img width="24" border="0" style="display: block;" src="http://i.imgur.com/Wji3af6.png" alt=""></a>
                                                        </td> --}}
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td height="60" style="font-size: 60px; line-height: 60px;">&nbsp;</td>
    </tr>

  </table>
  <!-- end section -->

  <!-- footer ====== -->
  {{-- <table border="0" width="100%" cellpadding="0" cellspacing="0" bgcolor="f4f4f4">

    <tr>
        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
    </tr>

    <tr>
        <td align="center">

            <table border="0" align="center" width="590" cellpadding="0" cellspacing="0" class="container590">

                <tr>
                    <td>
                        <table border="0" align="left" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                            class="container590">
                            <tr>
                                <td align="left" style="color: #aaaaaa; font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;">
                                    <div style="line-height: 24px;">

                                        <span style="color: #333333;">Material Design for Bootstrap</span>

                                    </div>
                                </td>
                            </tr>
                        </table>

                        <table border="0" align="left" width="5" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                            class="container590">
                            <tr>
                                <td height="20" width="5" style="font-size: 20px; line-height: 20px;">&nbsp;</td>
                            </tr>
                        </table>

                        <table border="0" align="right" cellpadding="0" cellspacing="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;"
                            class="container590">

                            <tr>
                                <td align="center">
                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center">
                                                <a style="font-size: 14px; font-family: 'Work Sans', Calibri, sans-serif; line-height: 24px;color: #5caad2; text-decoration: none;font-weight:bold;"
                                                    href="{{UnsubscribeURL}}">UNSUBSCRIBE</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>

    <tr>
        <td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td>
    </tr>
  </table> --}}
  <!-- end footer ====== -->
@endsection
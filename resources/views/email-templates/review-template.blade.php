<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Registration Mitra ONCUKUR</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style type="text/css">
    .container {
      max-width: 100%;
      margin: 0 150px;
    }
    .row{
      display: flex;
      justify-content: center;
    }
    .card{
      border: 1px solid #dbdbdb;
      padding: 0 50px;
      border-radius: 10px;
      box-shadow: 8px 3px 32px -7px rgba(92,92,92,1);;
    }
    .card-header{
      border-bottom: 1px solid #9e9e9e;
    }
    .card-header h2{
      margin-bottom: 10px;
    }
    .card-body{
      padding-top: 10px;
    }
    .card-body span{
      font-size: 16px;
    }
    .card-footer{
      border-top: 1px solid #9e9e9e;
      padding-bottom: 10px;
    }

    .card-footer .logo{
      margin-top: 5px;
      display: flex;
      justify-content: end;
    }
    .card-footer .logo img{
      height: 30px;
    }

    @media(max-width: 500px){
      .container{
        margin: 0 10px;
      }
    }

    /**
         * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
         */
    @media screen {
      @font-face {
        font-family: 'Source Sans Pro';
        font-style: normal;
        font-weight: 400;
        src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
      }

      @font-face {
        font-family: 'Source Sans Pro';
        font-style: normal;
        font-weight: 700;
        src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
      }
    }

    /**
         * Avoid browser level font resizing.
         * 1. Windows Mobile
         * 2. iOS / OSX
         */
    body,
    table,
    td,
    a {
      -ms-text-size-adjust: 100%;
      /* 1 */
      -webkit-text-size-adjust: 100%;
      /* 2 */
    }

    /**
         * Remove extra space added to tables and cells in Outlook.
         */
    table,
    td {
      mso-table-rspace: 0pt;
      mso-table-lspace: 0pt;
    }

    /**
         * Better fluid images in Internet Explorer.
         */
    img {
      -ms-interpolation-mode: bicubic;
    }

    /**
         * Remove blue links for iOS devices.
         */
    a[x-apple-data-detectors] {
      font-family: inherit !important;
      font-size: inherit !important;
      font-weight: inherit !important;
      line-height: inherit !important;
      color: inherit !important;
      text-decoration: none !important;
    }

    /**
         * Fix centering issues in Android 4.4.
         */
    div[style*="margin: 16px 0;"] {
      margin: 0 !important;
    }

    body {
      width: 100% !important;
      height: 100% !important;
      padding: 0 !important;
      margin: 0 !important;
    }

    /**
         * Collapse table borders to avoid space between cells.
         */
    table {
      border-collapse: collapse !important;
    }

    a {
      color: #1a82e2;
    }

    img {
      height: auto;
      line-height: 100%;
      text-decoration: none;
      border: 0;
      outline: none;
    }

  </style>
</head>

<body>
  <!-- end preheader -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h3>
                ONCUKUR Regisration Status
            </h3>
          </div>
          <div class="card-body">
            <span>

              Based on your registration at Mitra ONCUKUR: <br>
              <h4>
                {!! $token !!}
              </h4>
            </span>
          </div>
          <div class="card-footer">
            <div class="logo" style="display: flex; justify-content: right;">
                <img src="{{asset("assets/oncukur.png")}}" alt="ONCUKUR LOGO">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>

</html>

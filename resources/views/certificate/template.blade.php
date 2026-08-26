<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: serif;
            text-align: center;
            padding: 60px;
            border: 12px solid #17233F;
            margin: 0;
        }
        .inner-border {
            border: 2px solid #F2B705;
            padding: 50px;
            height: 100%;
        }
        .label {
            font-size: 14px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: #2F6F62;
            margin-bottom: 10px;
        }
        .title {
            font-size: 32px;
            color: #17233F;
            margin-bottom: 30px;
            font-weight: bold;
        }
        .given-to {
            font-size: 14px;
            color: #4B5566;
            margin-bottom: 5px;
        }
        .student-name {
            font-size: 30px;
            color: #17233F;
            margin-bottom: 25px;
            border-bottom: 1px solid #E4DFD2;
            display: inline-block;
            padding-bottom: 8px;
        }
        .desc {
            font-size: 14px;
            color: #4B5566;
            margin-bottom: 40px;
        }
        .course-name {
            font-weight: bold;
            color: #17233F;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            font-size: 12px;
            color: #4B5566;
        }
    </style>
</head>
<body>
    <div class="inner-border">
        <p class="label">GLE Academy</p>
        <p class="title">Sertifikat Penyelesaian</p>

        <p class="given-to">Diberikan kepada</p>
        <p class="student-name">{{ $user->name }}</p>

        <p class="desc">
            Atas keberhasilannya menyelesaikan course<br>
            <span class="course-name">"{{ $course->title }}"</span><br>
            yang diampu oleh {{ $course->instructor->name }}
        </p>

        <div class="footer">
            <div>Tanggal: {{ $date }}</div>
            <div>GLE Academy</div>
        </div>
    </div>
</body>
</html>
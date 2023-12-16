<!DOCTYPE html>

<html lang="id">
<head>
    <title>Slip Gaji</title>
    <meta charset="utf-8"/>
    <meta content="width=device-width" name="viewport"/>
    <style>
        .bee-row,
        .bee-row-content {
            position: relative;
        }

        body {
            background-color: #ffffff;
            color: #000000;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .bee-row-10 .bee-col-1 .bee-block-1 li a,
        .bee-row-10 .bee-col-4 .bee-block-1 li a,
        .bee-row-11 .bee-col-1 .bee-block-1 li a,
        .bee-row-11 .bee-col-4 .bee-block-1 li a,
        .bee-row-12 .bee-col-1 .bee-block-1 li a,
        .bee-row-13 .bee-col-1 .bee-block-1 li a,
        .bee-row-13 .bee-col-4 .bee-block-1 li a,
        .bee-row-14 .bee-col-1 .bee-block-1 li a,
        .bee-row-15 .bee-col-1 .bee-block-1 li a,
        .bee-row-16 .bee-col-1 .bee-block-1 li a,
        .bee-row-17 .bee-col-1 .bee-block-1 li a,
        .bee-row-17 .bee-col-4 .bee-block-1 li a,
        .bee-row-18 .bee-col-1 .bee-block-1 li a,
        .bee-row-22 .bee-col-1 .bee-block-1 li a,
        .bee-row-22 .bee-col-4 .bee-block-1 li a,
        .bee-row-23 .bee-col-1 .bee-block-1 li a,
        .bee-row-23 .bee-col-4 .bee-block-1 li a,
        .bee-row-24 .bee-col-1 .bee-block-1 li a,
        .bee-row-25 .bee-col-1 .bee-block-1 li a,
        .bee-row-26 .bee-col-1 .bee-block-1 li a,
        .bee-row-27 .bee-col-1 .bee-block-1 li a,
        .bee-row-35 .bee-col-1 .bee-block-1 a,
        .bee-row-36 .bee-col-1 .bee-block-1 a,
        .bee-row-36 .bee-col-2 .bee-block-1 a,
        .bee-row-6 .bee-col-1 .bee-block-1 li a,
        .bee-row-6 .bee-col-4 .bee-block-1 li a,
        .bee-row-7 .bee-col-1 .bee-block-1 li a,
        .bee-row-7 .bee-col-4 .bee-block-1 li a,
        .bee-row-8 .bee-col-1 .bee-block-1 li a,
        .bee-row-8 .bee-col-4 .bee-block-1 li a,
        .bee-row-9 .bee-col-1 .bee-block-1 li a,
        .bee-row-9 .bee-col-4 .bee-block-1 li a,
        a {
            color: #7747ff;
        }

        * {
            box-sizing: border-box;
        }

        body,
        h1,
        h3,
        p {
            margin: 0;
        }

        .bee-row-content {
            max-width: 1025px;
            margin: 0 auto;
            display: flex;
        }

        .bee-row-content .bee-col-w1 {
            flex-basis: 8%;
        }

        .bee-row-content .bee-col-w2 {
            flex-basis: 17%;
        }

        .bee-row-content .bee-col-w3 {
            flex-basis: 25%;
        }

        .bee-row-content .bee-col-w5 {
            flex-basis: 42%;
        }

        .bee-row-content .bee-col-w6 {
            flex-basis: 50%;
        }

        .bee-row-content .bee-col-w9 {
            flex-basis: 75%;
        }

        .bee-row-content .bee-col-w12 {
            flex-basis: 100%;
        }

        .bee-icon .bee-icon-label-right a {
            text-decoration: none;
        }

        .bee-image {
            overflow: auto;
        }

        .bee-row-1 .bee-col-1 .bee-block-1 {
            width: 100%;
        }

        .bee-row-1 .bee-row-content,
        .bee-row-2 .bee-row-content,
        .bee-row-36 .bee-row-content {
            background-repeat: no-repeat;
            border-left: 1px solid #000000;
            border-radius: 0;
            border-right: 1px solid #000000;
        }

        .bee-list ul {
            margin: 0;
            padding: 0;
        }

        .bee-icon {
            display: inline-block;
            vertical-align: middle;
        }

        .bee-icon .bee-content {
            display: flex;
            align-items: center;
        }

        .bee-image img {
            display: block;
            width: 100%;
        }

        .bee-paragraph {
            overflow-wrap: anywhere;
        }

        @media (max-width: 768px) {
            .bee-row-content:not(.no_stack) {
                display: block;
            }
        }

        .bee-row-1,
        .bee-row-10,
        .bee-row-11,
        .bee-row-12,
        .bee-row-13,
        .bee-row-14,
        .bee-row-15,
        .bee-row-16,
        .bee-row-17,
        .bee-row-18,
        .bee-row-19,
        .bee-row-2,
        .bee-row-20,
        .bee-row-21,
        .bee-row-22,
        .bee-row-23,
        .bee-row-24,
        .bee-row-25,
        .bee-row-26,
        .bee-row-27,
        .bee-row-28,
        .bee-row-29,
        .bee-row-3,
        .bee-row-30,
        .bee-row-31,
        .bee-row-32,
        .bee-row-33,
        .bee-row-34,
        .bee-row-35,
        .bee-row-36,
        .bee-row-4,
        .bee-row-5,
        .bee-row-6,
        .bee-row-7,
        .bee-row-8,
        .bee-row-9 {
            background-repeat: no-repeat;
        }

        .bee-row-1 .bee-row-content,
        .bee-row-36 .bee-row-content {
            border-bottom: 1px solid #000000;
            border-top: 1px solid #000000;
            color: #000000;
        }

        .bee-row-1 .bee-col-1 {
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bee-row-1 .bee-col-2,
        .bee-row-1 .bee-col-3 {
            padding-bottom: 10px;
            padding-top: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .bee-row-1 .bee-col-2 .bee-block-1,
        .bee-row-1 .bee-col-2 .bee-block-2,
        .bee-row-1 .bee-col-2 .bee-block-3,
        .bee-row-1 .bee-col-2 .bee-block-4 {
            padding: 5px 15px;
            text-align: center;
            width: 100%;
        }

        .bee-row-1 .bee-col-3 .bee-block-1,
        .bee-row-1 .bee-col-3 .bee-block-2 {
            padding: 5px;
            text-align: center;
            width: 100%;
        }

        .bee-row-2 .bee-row-content {
            border-bottom: 0 solid #000000;
            border-top: 0 solid #000000;
            color: #000000;
        }

        .bee-row-20 .bee-row-content,
        .bee-row-21 .bee-row-content,
        .bee-row-29 .bee-row-content,
        .bee-row-33 .bee-row-content,
        .bee-row-4 .bee-row-content,
        .bee-row-5 .bee-row-content {
            background-repeat: no-repeat;
            border-left: 1px solid #000000;
            border-radius: 0;
            border-top: 1px solid #000000;
            border-right: 1px solid #000000;
            color: #000000;
        }

        .bee-row-10 .bee-col-4 .bee-block-1,
        .bee-row-11 .bee-col-4 .bee-block-1,
        .bee-row-13 .bee-col-4 .bee-block-1,
        .bee-row-17 .bee-col-1 .bee-block-1,
        .bee-row-17 .bee-col-4 .bee-block-1,
        .bee-row-18 .bee-col-1 .bee-block-1,
        .bee-row-2 .bee-col-1,
        .bee-row-20 .bee-col-1,
        .bee-row-22 .bee-col-1 .bee-block-1,
        .bee-row-22 .bee-col-4 .bee-block-1,
        .bee-row-23 .bee-col-4 .bee-block-1,
        .bee-row-26 .bee-col-1 .bee-block-1,
        .bee-row-27 .bee-col-1 .bee-block-1,
        .bee-row-29 .bee-col-1,
        .bee-row-33 .bee-col-1,
        .bee-row-36 .bee-col-2,
        .bee-row-37 .bee-col-1,
        .bee-row-4 .bee-col-1,
        .bee-row-6 .bee-col-1 .bee-block-1,
        .bee-row-6 .bee-col-4 .bee-block-1,
        .bee-row-7 .bee-col-1 .bee-block-1,
        .bee-row-7 .bee-col-4 .bee-block-1,
        .bee-row-8 .bee-col-4 .bee-block-1,
        .bee-row-9 .bee-col-4 .bee-block-1 {
            padding-bottom: 5px;
            padding-top: 5px;
        }

        .bee-row-10 .bee-row-content,
        .bee-row-11 .bee-row-content,
        .bee-row-12 .bee-row-content,
        .bee-row-13 .bee-row-content,
        .bee-row-14 .bee-row-content,
        .bee-row-15 .bee-row-content,
        .bee-row-16 .bee-row-content,
        .bee-row-17 .bee-row-content,
        .bee-row-18 .bee-row-content,
        .bee-row-19 .bee-row-content,
        .bee-row-22 .bee-row-content,
        .bee-row-23 .bee-row-content,
        .bee-row-24 .bee-row-content,
        .bee-row-25 .bee-row-content,
        .bee-row-26 .bee-row-content,
        .bee-row-27 .bee-row-content,
        .bee-row-28 .bee-row-content,
        .bee-row-3 .bee-row-content,
        .bee-row-30 .bee-row-content,
        .bee-row-31 .bee-row-content,
        .bee-row-32 .bee-row-content,
        .bee-row-34 .bee-row-content,
        .bee-row-35 .bee-row-content,
        .bee-row-6 .bee-row-content,
        .bee-row-7 .bee-row-content,
        .bee-row-8 .bee-row-content,
        .bee-row-9 .bee-row-content {
            background-repeat: no-repeat;
            border-bottom: 0 solid #000000;
            border-left: 1px solid #000000;
            border-radius: 0;
            border-right: 1px solid #000000;
            border-top: 1px solid #000000;
            color: #000000;
        }

        .bee-row-3 .bee-col-1 .bee-block-1,
        .bee-row-3 .bee-col-1 .bee-block-2,
        .bee-row-3 .bee-col-1 .bee-block-3,
        .bee-row-3 .bee-col-1 .bee-block-4,
        .bee-row-3 .bee-col-1 .bee-block-5,
        .bee-row-30 .bee-col-1 .bee-block-1,
        .bee-row-31 .bee-col-1 .bee-block-1,
        .bee-row-32 .bee-col-1 .bee-block-1,
        .bee-row-34 .bee-col-1 .bee-block-1 {
            padding-bottom: 5px;
            padding-left: 10px;
            padding-top: 5px;
            text-align: center;
            width: 100%;
        }

        .bee-row-10 .bee-col-2 .bee-block-1,
        .bee-row-10 .bee-col-5 .bee-block-1,
        .bee-row-11 .bee-col-2 .bee-block-1,
        .bee-row-11 .bee-col-5 .bee-block-1,
        .bee-row-12 .bee-col-2 .bee-block-1,
        .bee-row-12 .bee-col-4 .bee-block-1,
        .bee-row-12 .bee-col-5 .bee-block-1,
        .bee-row-13 .bee-col-2 .bee-block-1,
        .bee-row-13 .bee-col-5 .bee-block-1,
        .bee-row-14 .bee-col-2 .bee-block-1,
        .bee-row-14 .bee-col-4 .bee-block-1,
        .bee-row-14 .bee-col-5 .bee-block-1,
        .bee-row-15 .bee-col-2 .bee-block-1,
        .bee-row-15 .bee-col-4 .bee-block-1,
        .bee-row-15 .bee-col-5 .bee-block-1,
        .bee-row-16 .bee-col-2 .bee-block-1,
        .bee-row-16 .bee-col-4 .bee-block-1,
        .bee-row-16 .bee-col-5 .bee-block-1,
        .bee-row-17 .bee-col-2 .bee-block-1,
        .bee-row-17 .bee-col-5 .bee-block-1,
        .bee-row-18 .bee-col-2 .bee-block-1,
        .bee-row-18 .bee-col-4 .bee-block-1,
        .bee-row-18 .bee-col-5 .bee-block-1,
        .bee-row-19 .bee-col-1 .bee-block-1,
        .bee-row-19 .bee-col-4 .bee-block-1,
        .bee-row-19 .bee-col-5 .bee-block-1,
        .bee-row-22 .bee-col-2 .bee-block-1,
        .bee-row-22 .bee-col-5 .bee-block-1,
        .bee-row-23 .bee-col-2 .bee-block-1,
        .bee-row-23 .bee-col-5 .bee-block-1,
        .bee-row-24 .bee-col-2 .bee-block-1,
        .bee-row-24 .bee-col-4 .bee-block-1,
        .bee-row-24 .bee-col-5 .bee-block-1,
        .bee-row-25 .bee-col-2 .bee-block-1,
        .bee-row-25 .bee-col-4 .bee-block-1,
        .bee-row-25 .bee-col-5 .bee-block-1,
        .bee-row-26 .bee-col-2 .bee-block-1,
        .bee-row-26 .bee-col-4 .bee-block-1,
        .bee-row-26 .bee-col-5 .bee-block-1,
        .bee-row-27 .bee-col-2 .bee-block-1,
        .bee-row-27 .bee-col-4 .bee-block-1,
        .bee-row-27 .bee-col-5 .bee-block-1,
        .bee-row-28 .bee-col-1 .bee-block-1,
        .bee-row-28 .bee-col-4 .bee-block-1,
        .bee-row-28 .bee-col-5 .bee-block-1,
        .bee-row-3 .bee-col-2 .bee-block-1,
        .bee-row-3 .bee-col-2 .bee-block-3,
        .bee-row-3 .bee-col-2 .bee-block-4,
        .bee-row-3 .bee-col-2 .bee-block-5,
        .bee-row-3 .bee-col-3 .bee-block-1,
        .bee-row-3 .bee-col-3 .bee-block-2,
        .bee-row-3 .bee-col-3 .bee-block-3,
        .bee-row-3 .bee-col-3 .bee-block-4,
        .bee-row-3 .bee-col-3 .bee-block-5,
        .bee-row-30 .bee-col-2 .bee-block-1,
        .bee-row-30 .bee-col-4 .bee-block-1,
        .bee-row-30 .bee-col-5 .bee-block-1,
        .bee-row-31 .bee-col-2 .bee-block-1,
        .bee-row-31 .bee-col-4 .bee-block-1,
        .bee-row-31 .bee-col-5 .bee-block-1,
        .bee-row-32 .bee-col-2 .bee-block-1,
        .bee-row-32 .bee-col-4 .bee-block-1,
        .bee-row-32 .bee-col-5 .bee-block-1,
        .bee-row-34 .bee-col-2 .bee-block-1,
        .bee-row-34 .bee-col-4 .bee-block-1,
        .bee-row-34 .bee-col-5 .bee-block-1,
        .bee-row-6 .bee-col-2 .bee-block-1,
        .bee-row-6 .bee-col-5 .bee-block-1,
        .bee-row-7 .bee-col-2 .bee-block-1,
        .bee-row-7 .bee-col-5 .bee-block-1,
        .bee-row-8 .bee-col-2 .bee-block-1,
        .bee-row-8 .bee-col-5 .bee-block-1,
        .bee-row-9 .bee-col-2 .bee-block-1,
        .bee-row-9 .bee-col-5 .bee-block-1 {
            padding-bottom: 5px;
            padding-top: 5px;
            text-align: center;
            width: 100%;
        }

        .bee-row-19 .bee-col-2 .bee-block-1,
        .bee-row-28 .bee-col-2 .bee-block-1,
        .bee-row-3 .bee-col-2 .bee-block-2 {
            padding: 5px 10px;
            text-align: center;
            width: 100%;
        }

        .bee-row-21 .bee-row-content,
        .bee-row-5 .bee-row-content {
            background-color: #01ebff;
            border-bottom: 0 solid #000000;
        }

        .bee-row-10 .bee-col-3,
        .bee-row-11 .bee-col-3,
        .bee-row-12 .bee-col-3,
        .bee-row-13 .bee-col-3,
        .bee-row-14 .bee-col-3,
        .bee-row-15 .bee-col-3,
        .bee-row-16 .bee-col-3,
        .bee-row-17 .bee-col-3,
        .bee-row-18 .bee-col-3,
        .bee-row-21 .bee-col-1,
        .bee-row-22 .bee-col-3,
        .bee-row-23 .bee-col-3,
        .bee-row-24 .bee-col-3,
        .bee-row-25 .bee-col-3,
        .bee-row-26 .bee-col-3,
        .bee-row-27 .bee-col-3,
        .bee-row-5 .bee-col-1,
        .bee-row-6 .bee-col-3,
        .bee-row-7 .bee-col-3,
        .bee-row-8 .bee-col-3,
        .bee-row-9 .bee-col-3 {
            border-right: 1px solid #000000;
        }

        .bee-row-21 .bee-col-1 .bee-block-1,
        .bee-row-21 .bee-col-2 .bee-block-1,
        .bee-row-5 .bee-col-1 .bee-block-1,
        .bee-row-5 .bee-col-2 .bee-block-1 {
            padding: 10px;
            text-align: center;
            width: 100%;
        }

        .bee-row-10 .bee-col-1,
        .bee-row-10 .bee-col-4,
        .bee-row-11 .bee-col-1,
        .bee-row-11 .bee-col-4,
        .bee-row-12 .bee-col-1,
        .bee-row-13 .bee-col-1,
        .bee-row-13 .bee-col-4,
        .bee-row-14 .bee-col-1,
        .bee-row-15 .bee-col-1,
        .bee-row-16 .bee-col-1,
        .bee-row-17 .bee-col-1,
        .bee-row-17 .bee-col-4,
        .bee-row-18 .bee-col-1,
        .bee-row-22 .bee-col-1,
        .bee-row-22 .bee-col-4,
        .bee-row-23 .bee-col-1,
        .bee-row-23 .bee-col-4,
        .bee-row-24 .bee-col-1,
        .bee-row-25 .bee-col-1,
        .bee-row-26 .bee-col-1,
        .bee-row-27 .bee-col-1,
        .bee-row-6 .bee-col-1,
        .bee-row-6 .bee-col-4,
        .bee-row-7 .bee-col-1,
        .bee-row-7 .bee-col-4,
        .bee-row-8 .bee-col-1,
        .bee-row-8 .bee-col-4,
        .bee-row-9 .bee-col-1,
        .bee-row-9 .bee-col-4 {
            padding-left: 10px;
        }

        .bee-row-10 .bee-col-3 .bee-block-1,
        .bee-row-10 .bee-col-6 .bee-block-1,
        .bee-row-11 .bee-col-3 .bee-block-1,
        .bee-row-11 .bee-col-6 .bee-block-1,
        .bee-row-12 .bee-col-3 .bee-block-1,
        .bee-row-12 .bee-col-6 .bee-block-1,
        .bee-row-13 .bee-col-3 .bee-block-1,
        .bee-row-13 .bee-col-6 .bee-block-1,
        .bee-row-14 .bee-col-3 .bee-block-1,
        .bee-row-14 .bee-col-6 .bee-block-1,
        .bee-row-15 .bee-col-3 .bee-block-1,
        .bee-row-15 .bee-col-6 .bee-block-1,
        .bee-row-16 .bee-col-3 .bee-block-1,
        .bee-row-16 .bee-col-6 .bee-block-1,
        .bee-row-17 .bee-col-3 .bee-block-1,
        .bee-row-17 .bee-col-6 .bee-block-1,
        .bee-row-18 .bee-col-3 .bee-block-1,
        .bee-row-18 .bee-col-6 .bee-block-1,
        .bee-row-19 .bee-col-3 .bee-block-1,
        .bee-row-19 .bee-col-6 .bee-block-1,
        .bee-row-22 .bee-col-3 .bee-block-1,
        .bee-row-22 .bee-col-6 .bee-block-1,
        .bee-row-23 .bee-col-3 .bee-block-1,
        .bee-row-23 .bee-col-6 .bee-block-1,
        .bee-row-24 .bee-col-3 .bee-block-1,
        .bee-row-24 .bee-col-6 .bee-block-1,
        .bee-row-25 .bee-col-3 .bee-block-1,
        .bee-row-25 .bee-col-6 .bee-block-1,
        .bee-row-26 .bee-col-3 .bee-block-1,
        .bee-row-26 .bee-col-6 .bee-block-1,
        .bee-row-27 .bee-col-3 .bee-block-1,
        .bee-row-27 .bee-col-6 .bee-block-1,
        .bee-row-28 .bee-col-3 .bee-block-1,
        .bee-row-28 .bee-col-6 .bee-block-1,
        .bee-row-30 .bee-col-3 .bee-block-1,
        .bee-row-30 .bee-col-6 .bee-block-1,
        .bee-row-31 .bee-col-3 .bee-block-1,
        .bee-row-31 .bee-col-6 .bee-block-1,
        .bee-row-32 .bee-col-3 .bee-block-1,
        .bee-row-32 .bee-col-6 .bee-block-1,
        .bee-row-34 .bee-col-3 .bee-block-1,
        .bee-row-34 .bee-col-6 .bee-block-1,
        .bee-row-6 .bee-col-3 .bee-block-1,
        .bee-row-6 .bee-col-6 .bee-block-1,
        .bee-row-7 .bee-col-3 .bee-block-1,
        .bee-row-7 .bee-col-6 .bee-block-1,
        .bee-row-8 .bee-col-3 .bee-block-1,
        .bee-row-8 .bee-col-6 .bee-block-1,
        .bee-row-9 .bee-col-3 .bee-block-1,
        .bee-row-9 .bee-col-6 .bee-block-1 {
            padding-bottom: 5px;
            padding-right: 5px;
            padding-top: 5px;
            text-align: center;
            width: 100%;
        }

        .bee-row-10 .bee-col-1 .bee-block-1,
        .bee-row-11 .bee-col-1 .bee-block-1,
        .bee-row-12 .bee-col-1 .bee-block-1,
        .bee-row-13 .bee-col-1 .bee-block-1,
        .bee-row-14 .bee-col-1 .bee-block-1,
        .bee-row-15 .bee-col-1 .bee-block-1,
        .bee-row-16 .bee-col-1 .bee-block-1,
        .bee-row-23 .bee-col-1 .bee-block-1,
        .bee-row-24 .bee-col-1 .bee-block-1,
        .bee-row-25 .bee-col-1 .bee-block-1,
        .bee-row-8 .bee-col-1 .bee-block-1,
        .bee-row-9 .bee-col-1 .bee-block-1 {
            padding-bottom: 5px;
            padding-left: 15px;
            padding-top: 5px;
        }

        .bee-row-12 .bee-col-4,
        .bee-row-12 .bee-col-5,
        .bee-row-12 .bee-col-6,
        .bee-row-19 .bee-col-1,
        .bee-row-19 .bee-col-2,
        .bee-row-24 .bee-col-4,
        .bee-row-24 .bee-col-5,
        .bee-row-24 .bee-col-6,
        .bee-row-28 .bee-col-1,
        .bee-row-28 .bee-col-2 {
            background-color: #d1d1d1;
        }

        .bee-row-19 .bee-col-3,
        .bee-row-28 .bee-col-3 {
            background-color: #d1d1d1;
            border-right: 1px solid #000000;
        }

        .bee-row-37,
        .bee-row-37 .bee-row-content {
            background-color: #ffffff;
            background-repeat: no-repeat;
        }

        .bee-row-35 .bee-col-1 .bee-block-1,
        .bee-row-36 .bee-col-1 .bee-block-1,
        .bee-row-36 .bee-col-2 .bee-block-1 {
            padding: 5px 10px;
        }

        .bee-row-37 .bee-row-content {
            color: #000000;
        }

        .bee-row-37 .bee-col-1 .bee-block-1 {
            color: #1e0e4b;
            font-family: Inter, sans-serif;
            font-size: 15px;
            padding-bottom: 5px;
            padding-top: 5px;
            text-align: center;
        }

        .bee-row-10 .bee-col-1 .bee-block-1,
        .bee-row-10 .bee-col-4 .bee-block-1,
        .bee-row-11 .bee-col-1 .bee-block-1,
        .bee-row-11 .bee-col-4 .bee-block-1,
        .bee-row-12 .bee-col-1 .bee-block-1,
        .bee-row-13 .bee-col-1 .bee-block-1,
        .bee-row-13 .bee-col-4 .bee-block-1,
        .bee-row-14 .bee-col-1 .bee-block-1,
        .bee-row-15 .bee-col-1 .bee-block-1,
        .bee-row-16 .bee-col-1 .bee-block-1,
        .bee-row-17 .bee-col-1 .bee-block-1,
        .bee-row-17 .bee-col-4 .bee-block-1,
        .bee-row-18 .bee-col-1 .bee-block-1,
        .bee-row-22 .bee-col-1 .bee-block-1,
        .bee-row-22 .bee-col-4 .bee-block-1,
        .bee-row-23 .bee-col-1 .bee-block-1,
        .bee-row-23 .bee-col-4 .bee-block-1,
        .bee-row-24 .bee-col-1 .bee-block-1,
        .bee-row-25 .bee-col-1 .bee-block-1,
        .bee-row-26 .bee-col-1 .bee-block-1,
        .bee-row-27 .bee-col-1 .bee-block-1,
        .bee-row-6 .bee-col-1 .bee-block-1,
        .bee-row-6 .bee-col-4 .bee-block-1,
        .bee-row-7 .bee-col-1 .bee-block-1,
        .bee-row-7 .bee-col-4 .bee-block-1,
        .bee-row-8 .bee-col-1 .bee-block-1,
        .bee-row-8 .bee-col-4 .bee-block-1,
        .bee-row-9 .bee-col-1 .bee-block-1,
        .bee-row-9 .bee-col-4 .bee-block-1 {
            color: #101218;
            direction: ltr;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0;
            line-height: 120%;
            text-align: left;
        }

        .bee-row-10 .bee-col-4 .bee-block-1 ul,
        .bee-row-11 .bee-col-4 .bee-block-1 ul,
        .bee-row-13 .bee-col-4 .bee-block-1 ul,
        .bee-row-17 .bee-col-1 .bee-block-1 ul,
        .bee-row-17 .bee-col-4 .bee-block-1 ul,
        .bee-row-18 .bee-col-1 .bee-block-1 ul,
        .bee-row-22 .bee-col-1 .bee-block-1 ul,
        .bee-row-22 .bee-col-4 .bee-block-1 ul,
        .bee-row-23 .bee-col-4 .bee-block-1 ul,
        .bee-row-26 .bee-col-1 .bee-block-1 ul,
        .bee-row-27 .bee-col-1 .bee-block-1 ul,
        .bee-row-6 .bee-col-1 .bee-block-1 ul,
        .bee-row-6 .bee-col-4 .bee-block-1 ul,
        .bee-row-7 .bee-col-1 .bee-block-1 ul,
        .bee-row-7 .bee-col-4 .bee-block-1 ul,
        .bee-row-8 .bee-col-4 .bee-block-1 ul,
        .bee-row-9 .bee-col-4 .bee-block-1 ul {
            list-style-type: revert;
            list-style-position: inside;
        }

        .bee-row-10 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-10 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-11 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-11 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-12 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-13 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-13 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-14 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-15 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-16 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-17 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-17 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-18 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-22 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-22 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-23 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-23 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-24 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-25 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-26 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-27 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-6 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-6 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-7 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-7 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-8 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-8 .bee-col-4 .bee-block-1 li:not(:last-child),
        .bee-row-9 .bee-col-1 .bee-block-1 li:not(:last-child),
        .bee-row-9 .bee-col-4 .bee-block-1 li:not(:last-child) {
            margin-bottom: 0;
        }

        .bee-row-10 .bee-col-1 .bee-block-1 li ul,
        .bee-row-10 .bee-col-4 .bee-block-1 li ul,
        .bee-row-11 .bee-col-1 .bee-block-1 li ul,
        .bee-row-11 .bee-col-4 .bee-block-1 li ul,
        .bee-row-12 .bee-col-1 .bee-block-1 li ul,
        .bee-row-13 .bee-col-1 .bee-block-1 li ul,
        .bee-row-13 .bee-col-4 .bee-block-1 li ul,
        .bee-row-14 .bee-col-1 .bee-block-1 li ul,
        .bee-row-15 .bee-col-1 .bee-block-1 li ul,
        .bee-row-16 .bee-col-1 .bee-block-1 li ul,
        .bee-row-17 .bee-col-1 .bee-block-1 li ul,
        .bee-row-17 .bee-col-4 .bee-block-1 li ul,
        .bee-row-18 .bee-col-1 .bee-block-1 li ul,
        .bee-row-22 .bee-col-1 .bee-block-1 li ul,
        .bee-row-22 .bee-col-4 .bee-block-1 li ul,
        .bee-row-23 .bee-col-1 .bee-block-1 li ul,
        .bee-row-23 .bee-col-4 .bee-block-1 li ul,
        .bee-row-24 .bee-col-1 .bee-block-1 li ul,
        .bee-row-25 .bee-col-1 .bee-block-1 li ul,
        .bee-row-26 .bee-col-1 .bee-block-1 li ul,
        .bee-row-27 .bee-col-1 .bee-block-1 li ul,
        .bee-row-6 .bee-col-1 .bee-block-1 li ul,
        .bee-row-6 .bee-col-4 .bee-block-1 li ul,
        .bee-row-7 .bee-col-1 .bee-block-1 li ul,
        .bee-row-7 .bee-col-4 .bee-block-1 li ul,
        .bee-row-8 .bee-col-1 .bee-block-1 li ul,
        .bee-row-8 .bee-col-4 .bee-block-1 li ul,
        .bee-row-9 .bee-col-1 .bee-block-1 li ul,
        .bee-row-9 .bee-col-4 .bee-block-1 li ul {
            margin-top: 0;
        }

        .bee-row-10 .bee-col-1 .bee-block-1 li li,
        .bee-row-10 .bee-col-4 .bee-block-1 li li,
        .bee-row-11 .bee-col-1 .bee-block-1 li li,
        .bee-row-11 .bee-col-4 .bee-block-1 li li,
        .bee-row-12 .bee-col-1 .bee-block-1 li li,
        .bee-row-13 .bee-col-1 .bee-block-1 li li,
        .bee-row-13 .bee-col-4 .bee-block-1 li li,
        .bee-row-14 .bee-col-1 .bee-block-1 li li,
        .bee-row-15 .bee-col-1 .bee-block-1 li li,
        .bee-row-16 .bee-col-1 .bee-block-1 li li,
        .bee-row-17 .bee-col-1 .bee-block-1 li li,
        .bee-row-17 .bee-col-4 .bee-block-1 li li,
        .bee-row-18 .bee-col-1 .bee-block-1 li li,
        .bee-row-22 .bee-col-1 .bee-block-1 li li,
        .bee-row-22 .bee-col-4 .bee-block-1 li li,
        .bee-row-23 .bee-col-1 .bee-block-1 li li,
        .bee-row-23 .bee-col-4 .bee-block-1 li li,
        .bee-row-24 .bee-col-1 .bee-block-1 li li,
        .bee-row-25 .bee-col-1 .bee-block-1 li li,
        .bee-row-26 .bee-col-1 .bee-block-1 li li,
        .bee-row-27 .bee-col-1 .bee-block-1 li li,
        .bee-row-6 .bee-col-1 .bee-block-1 li li,
        .bee-row-6 .bee-col-4 .bee-block-1 li li,
        .bee-row-7 .bee-col-1 .bee-block-1 li li,
        .bee-row-7 .bee-col-4 .bee-block-1 li li,
        .bee-row-8 .bee-col-1 .bee-block-1 li li,
        .bee-row-8 .bee-col-4 .bee-block-1 li li,
        .bee-row-9 .bee-col-1 .bee-block-1 li li,
        .bee-row-9 .bee-col-4 .bee-block-1 li li {
            margin-left: 30px;
        }

        .bee-row-10 .bee-col-1 .bee-block-1 ul,
        .bee-row-11 .bee-col-1 .bee-block-1 ul,
        .bee-row-12 .bee-col-1 .bee-block-1 ul,
        .bee-row-13 .bee-col-1 .bee-block-1 ul,
        .bee-row-14 .bee-col-1 .bee-block-1 ul,
        .bee-row-15 .bee-col-1 .bee-block-1 ul,
        .bee-row-16 .bee-col-1 .bee-block-1 ul,
        .bee-row-23 .bee-col-1 .bee-block-1 ul,
        .bee-row-24 .bee-col-1 .bee-block-1 ul,
        .bee-row-25 .bee-col-1 .bee-block-1 ul,
        .bee-row-8 .bee-col-1 .bee-block-1 ul,
        .bee-row-9 .bee-col-1 .bee-block-1 ul {
            list-style-type: circle;
            list-style-position: inside;
        }

        .bee-row-35 .bee-col-1 .bee-block-1 {
            color: #444a5b;
            direction: ltr;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0;
            line-height: 120%;
            text-align: left;
        }

        .bee-row-35 .bee-col-1 .bee-block-1 p:not(:last-child),
        .bee-row-36 .bee-col-1 .bee-block-1 p:not(:last-child),
        .bee-row-36 .bee-col-2 .bee-block-1 p:not(:last-child) {
            margin-bottom: 16px;
        }

        .bee-row-36 .bee-col-1 .bee-block-1,
        .bee-row-36 .bee-col-2 .bee-block-1 {
            color: #444a5b;
            direction: ltr;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0;
            line-height: 120%;
            text-align: center;
        }

        .bee-row-37 .bee-col-1 .bee-block-1 .bee-icon-image {
            padding: 5px 6px 5px 5px;
        }

        .bee-row-37
        .bee-col-1
        .bee-block-1
        .bee-icon:not(.bee-icon-first)
        .bee-content {
            margin-left: 0;
        }

        .bee-row-37
        .bee-col-1
        .bee-block-1
        .bee-icon::not(.bee-icon-last)
        .bee-content {
            margin-right: 0;
        }
    </style>
</head>
<body>
<div class="bee-page-container">
    <div class="bee-row bee-row-1">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w5">
                <div class="bee-block bee-block-1 bee-image">
                    <img
                        alt=""
                        class="bee-fixedwidth"
                        src="https://hrmsyarsi.netlify.app/logo.png"
                        style="max-width: 385px"
                    />
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w5">
                <div class="bee-block bee-block-1 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 12px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: justify;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                <span class="tinyMce-placeholder"
                >Jl. Letjen Suprapto No.Kav 13 10, RT.10/RW.5, Cemp. Putih
                  Tim., Kec. Cemp. Putih, Kota Jakarta Pusat, Daerah Khusus
                  Ibukota Jakarta 10510</span
                >
                    </h3>
                </div>
                <div class="bee-block bee-block-2 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 12px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: justify;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        Telepon : (021) 80618618 / 80618619
                    </h3>
                </div>
                <div class="bee-block bee-block-3 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 12px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: justify;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Fax : (021) 4243171</span>
                    </h3>
                </div>
                <div class="bee-block bee-block-4 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 12px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: justify;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        https://rsyarsi.co.id/
                    </h3>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 22px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">SLIP GAJI</span>
                    </h3>
                </div>
                <div class="bee-block bee-block-2 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">NOVEMBER 2023</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-2">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w12">
                <div class="bee-block bee-block-1 bee-spacer">
                    <div class="spacer" style="height: 15px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-3">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">NIK</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-2 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        NAMA KARYAWAN
                    </h1>
                </div>
                <div class="bee-block bee-block-3 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">DEPARTEMEN</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-4 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">JABATAN</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-5 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">MARITAL STATUS</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-2 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-3 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-4 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-5 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w9">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">NIK DISINI</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-2 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">NAMA KARYAWAN DISINI</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-3 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">DEPARTEMEN DISINI</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-4 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">JABATAN DISINI</span>
                    </h1>
                </div>
                <div class="bee-block bee-block-5 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">MARITAL STATUS DISINI</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-4">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w12">
                <div class="bee-block bee-block-1 bee-spacer">
                    <div class="spacer" style="height: 15px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-5">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w6">
                <div class="bee-block bee-block-1 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">PENDAPATAN TETAP</span>
                    </h3>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w6">
                <div class="bee-block bee-block-1 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        POTONGAN - POTONGAN
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-6">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul>
                        <li>Gaji Pokok</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>BPJS Ketenagakerjaan (Karyawan) JHT (2%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-7">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>BPJS Jaminan Pensiun (Karyawan)(1%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-8">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Transportasi</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>BPJS Kesehatan (Karyawan) (1%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-9">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Makan</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Voucher Makan</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-10">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Kemahalan</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Potongan Kehadiran</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-11">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Jabatan</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Potongan Lain - lain</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-12">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Dinas Malam</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Sub Total</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-13">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan PPR</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Hari Raya (THR)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-14">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Hospital On Duty</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-15">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Insentive Khusus</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-16">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Tunjangan Extra Fooding</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-17">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Uang Lembur</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>PPH 21</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-18">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Pengembalian Potongan / Rapel</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-19">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Sub Total</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-20">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w12">
                <div class="bee-block bee-block-1 bee-spacer">
                    <div class="spacer" style="height: 15px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-21">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w6">
                <div class="bee-block bee-block-1 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                <span class="tinyMce-placeholder"
                >TUNJANGAN TIDAK LANGSUNG</span
                >
                    </h3>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w6">
                <div class="bee-block bee-block-1 bee-heading">
                    <h3
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 16px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">ZAKAT</span>
                    </h3>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-22">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>BPJS Tenaga Kerja (Perusahaan)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>THP Sebelum Zakat</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-23">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>JHT (3,7%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>Zakat</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-24">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>JKK (0,24%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">THP Sesudah Zakat</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-25">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>JKM (0,30%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-26">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>BPJS Jaminan Pensiun (Perusahaan) (2%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-27">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-list">
                    <ul start="1">
                        <li>BPJS Kesehatan (Perusahaan) (4%)</li>
                    </ul>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-28">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Sub Total</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">Rp. 100.000.000</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-29">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w12">
                <div class="bee-block bee-block-1 bee-spacer">
                    <div class="spacer" style="height: 15px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-30">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">KETERANGAN</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-31">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">No. BPJS Tenaga Kerja</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">12121212121212</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-32">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">No. BPJS Kesehatan </span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">:</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">2121212.21212.2112.1</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-33">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w12">
                <div class="bee-block bee-block-1 bee-spacer">
                    <div class="spacer" style="height: 15px"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-34">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder">CATATAN</span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-3 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-4 bee-col-w3">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 700;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: left;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-5 bee-col-w1">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: center;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
            <div class="bee-col bee-col-6 bee-col-w2">
                <div class="bee-block bee-block-1 bee-heading">
                    <h1
                        style="
                  color: #1e0e4b;
                  direction: ltr;
                  font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
                  font-size: 14px;
                  font-weight: 400;
                  letter-spacing: normal;
                  line-height: 120%;
                  text-align: right;
                  margin-top: 0;
                  margin-bottom: 0;
                "
                    >
                        <span class="tinyMce-placeholder"></span>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-35">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w12">
                <div class="bee-block bee-block-1 bee-paragraph">
                    <p>Catatan tambahan disini</p>
                    <p> </p>
                    <p> </p>
                    <p> </p>
                    <p> </p>
                </div>
            </div>
        </div>
    </div>
    <div class="bee-row bee-row-36">
        <div class="bee-row-content">
            <div class="bee-col bee-col-1 bee-col-w6">
                <div class="bee-block bee-block-1 bee-paragraph">
                    <p>Manager Sumber Daya Manusia (SDM)</p>
                    <p> </p>
                    <p> </p>
                    <p> </p>
                    <p>(Ns. Suatmaji, M.Kep)</p>
                </div>
            </div>
            <div class="bee-col bee-col-2 bee-col-w6">
                <div class="bee-block bee-block-1 bee-paragraph">
                    <p>Karyawan</p>
                    <p> </p>
                    <p> </p>
                    <p> </p>
                    <p> </p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

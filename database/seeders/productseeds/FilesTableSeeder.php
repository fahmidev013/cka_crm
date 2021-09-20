<?php

namespace Database\Seeders\ProductSeeds;

use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('files')->delete();
        
        \DB::table('files')->insert(array (
            0 => 
            array (
                'id' => 1,
                'object_type' => 'Item',
                'object_id' => 2,
                'uploaded_by' => NULL,
                'file_name' => '4ca944fa029717f1df765e9aa9f22091_1_iphone-11-pro-select-2019-family.jpg',
                'original_file_name' => 'iphone-11-pro-select-2019-family.jpg',
                'created_at' => '2020-04-08 13:03:43',
            ),
            1 => 
            array (
                'id' => 2,
                'object_type' => 'Item',
                'object_id' => 3,
                'uploaded_by' => NULL,
                'file_name' => 'e6a2659da1bf086cf5daa79a98fab48a_1_apple-iphone-11-1-500x500.jpg',
                'original_file_name' => 'Apple-iPhone-11-1-500x500.jpg',
                'created_at' => '2020-04-08 13:16:28',
            ),
            2 => 
            array (
                'id' => 3,
                'object_type' => 'Item',
                'object_id' => 4,
                'uploaded_by' => NULL,
                'file_name' => '0fc9b92d5f637436be9d41565bec4834_1_samsung-galaxy-s10-1.jpg',
                'original_file_name' => 'samsung-galaxy-s10-1.jpg',
                'created_at' => '2020-04-08 13:27:10',
            ),
            3 => 
            array (
                'id' => 4,
                'object_type' => 'Item',
                'object_id' => 5,
                'uploaded_by' => NULL,
                'file_name' => '750e1779266a73e1e9c6eca0d6afb930_1_huawei-p30-pro.jpg',
                'original_file_name' => 'huawei-p30-pro.jpg',
                'created_at' => '2020-04-08 13:31:51',
            ),
            4 => 
            array (
                'id' => 5,
                'object_type' => 'Item',
                'object_id' => 6,
                'uploaded_by' => NULL,
                'file_name' => 'bde66b8ef0bfe6079512f5ab72728462_1_apple_watch_series_4.jpg',
                'original_file_name' => 'apple_watch_series_4.jpg',
                'created_at' => '2020-04-08 13:55:37',
            ),
            5 => 
            array (
                'id' => 6,
                'object_type' => 'Item',
                'object_id' => 7,
                'uploaded_by' => NULL,
                'file_name' => '5af4234ffc3fce78d3fc291b2d3037ff_1_103921.jpg',
                'original_file_name' => '103921.jpg',
                'created_at' => '2020-04-08 16:30:32',
            ),
            6 => 
            array (
                'id' => 7,
                'object_type' => 'Item',
                'object_id' => 8,
                'uploaded_by' => NULL,
                'file_name' => '5cec338c8dfd52a2f06f4a9436a5ffe4_1_havit.jpg',
                'original_file_name' => 'havit.jpg',
                'created_at' => '2020-04-08 16:36:36',
            ),
            7 => 
            array (
                'id' => 8,
                'object_type' => 'Item',
                'object_id' => 9,
                'uploaded_by' => NULL,
                'file_name' => 'a121d01996be8872e6a474d580697db4_1_81n-9sdsr6l.jpg',
                'original_file_name' => '81n-9sDsr6L._AC_SL1500_.jpg',
                'created_at' => '2020-04-08 16:46:50',
            ),
            8 => 
            array (
                'id' => 9,
                'object_type' => 'Item',
                'object_id' => 10,
                'uploaded_by' => NULL,
                'file_name' => 'c26a00baac0634d0a1ba4c2d18828060_1_corporate-event.jpg',
                'original_file_name' => 'corporate-event.jpg',
                'created_at' => '2020-04-08 16:52:31',
            ),
            9 => 
            array (
                'id' => 10,
                'object_type' => 'Item',
                'object_id' => 12,
                'uploaded_by' => NULL,
                'file_name' => '4bfb89b115e2f85165f5f1fb969e9b85_1_ufd110d4da3f148d5a336d7895d0e5ed9k.jpg',
                'original_file_name' => 'Ufd110d4da3f148d5a336d7895d0e5ed9k.jpg',
                'created_at' => '2020-04-08 16:56:58',
            ),
            10 => 
            array (
                'id' => 13,
                'object_type' => 'Item',
                'object_id' => 11,
                'uploaded_by' => NULL,
                'file_name' => '8cb97e13b9f2bebb83e3fe465b3681d3_1_jsygavpspkes2scjeihq_em2c5950-620x413.jpg',
                'original_file_name' => 'jsygaVpSPKes2SCJeihQ_EM2C5950-620x413.jpg',
                'created_at' => '2020-04-08 17:01:40',
            ),
            11 => 
            array (
                'id' => 14,
                'object_type' => 'Item',
                'object_id' => 13,
                'uploaded_by' => NULL,
                'file_name' => '69794a32093232e88029158b651fd61b_1_kelloggs-corn-flakes-real-mango-300-gm-1014139.jpg',
                'original_file_name' => 'Kelloggs-Corn-Flakes-Real-Mango-300-gm-1014139.jpg',
                'created_at' => '2020-04-08 17:02:47',
            ),
            12 => 
            array (
                'id' => 15,
                'object_type' => 'Item',
                'object_id' => 14,
                'uploaded_by' => NULL,
                'file_name' => '9b5d5fe35d9e385b66709f46d192cb5a_1_15_v_16_macbookpro_thumb1200_4-3.jpg',
                'original_file_name' => '15_v_16_macbookpro_thumb1200_4-3.jpg',
                'created_at' => '2020-04-08 17:11:13',
            ),
            13 => 
            array (
                'id' => 16,
                'object_type' => 'Item',
                'object_id' => 15,
                'uploaded_by' => NULL,
                'file_name' => 'aa7c68ad57d5e1ea3aa94569fdbf17f6_1_148821-laptops-review-apple-macbook-air-2019-review-image3-ymwb8qupgi.jpg',
                'original_file_name' => '148821-laptops-review-apple-macbook-air-2019-review-image3-ymwb8qupgi.jpg',
                'created_at' => '2020-04-08 17:14:06',
            ),
            14 => 
            array (
                'id' => 17,
                'object_type' => 'Direct Order',
                'object_id' => 2,
                'uploaded_by' => NULL,
                'file_name' => 'ed82981117b2971560a3174205e9ae3b_1_apple_watch_series_4.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 17:27:30',
            ),
            15 => 
            array (
                'id' => 19,
                'object_type' => 'Direct Order',
                'object_id' => 2,
                'uploaded_by' => NULL,
                'file_name' => '24862066b49b04b5617ac230adba986b_1_Apple-iPhone-11-1-500x500.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 17:28:12',
            ),
            16 => 
            array (
                'id' => 20,
                'object_type' => 'Direct Order',
                'object_id' => 3,
                'uploaded_by' => NULL,
                'file_name' => '6efc9b5b96f9087c88322103ae32e0e4_2_corporate-event.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 17:29:53',
            ),
            17 => 
            array (
                'id' => 21,
                'object_type' => 'Direct Order',
                'object_id' => 4,
                'uploaded_by' => NULL,
                'file_name' => 'f8209f41888a6e3025ab7c49521e7f61_1_Apple-iPhone-11-1-500x500.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:06:31',
            ),
            18 => 
            array (
                'id' => 22,
                'object_type' => 'Direct Order',
                'object_id' => 4,
                'uploaded_by' => NULL,
                'file_name' => 'a62aa036b2c4065cc3b0fe40ddc39945_1_iphone-11-pro-select-2019-family.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:06:31',
            ),
            19 => 
            array (
                'id' => 23,
                'object_type' => 'Direct Order',
                'object_id' => 5,
                'uploaded_by' => NULL,
                'file_name' => '72a43eb366592612dd0064039b81d220_1_huawei-p30-pro.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:07:35',
            ),
            20 => 
            array (
                'id' => 24,
                'object_type' => 'Direct Order',
                'object_id' => 5,
                'uploaded_by' => NULL,
                'file_name' => '2eac21b3a9196c93285ff5216f0438a1_1_samsung-galaxy-s10-1.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:07:35',
            ),
            21 => 
            array (
                'id' => 25,
                'object_type' => 'Direct Order',
                'object_id' => 6,
                'uploaded_by' => NULL,
                'file_name' => '4dedfdf46342b6098a2935d5d22807df_2_apple_watch_series_4.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:09:19',
            ),
            22 => 
            array (
                'id' => 26,
                'object_type' => 'Direct Order',
                'object_id' => 6,
                'uploaded_by' => NULL,
                'file_name' => 'f39c3578c189e14b1545b75c42d292f1_2_havit.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:09:19',
            ),
            23 => 
            array (
                'id' => 27,
                'object_type' => 'Direct Order',
                'object_id' => 6,
                'uploaded_by' => NULL,
                'file_name' => 'ac2bf9bf8fda779f6a73bf5d4d75cf6d_2_Apple-iPhone-11-1-500x500.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:09:19',
            ),
            24 => 
            array (
                'id' => 28,
                'object_type' => 'Direct Order',
                'object_id' => 7,
                'uploaded_by' => NULL,
                'file_name' => '22bb7150f5061f2aa69ea531bfe22225_3_15_v_16_macbookpro_thumb1200_4-3.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:11:44',
            ),
            25 => 
            array (
                'id' => 29,
                'object_type' => 'Direct Order',
                'object_id' => 8,
                'uploaded_by' => NULL,
                'file_name' => '2bfa482353bf190f1682032b05a050b4_5_corporate-event.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:13:04',
            ),
            26 => 
            array (
                'id' => 30,
                'object_type' => 'Direct Invoice',
                'object_id' => 9,
                'uploaded_by' => NULL,
                'file_name' => '365b256ce7ec7e1ac3495729c2ac33ea_1_huawei-p30-pro.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:13:36',
            ),
            27 => 
            array (
                'id' => 31,
                'object_type' => 'Direct Invoice',
                'object_id' => 9,
                'uploaded_by' => NULL,
                'file_name' => '365b256ce7ec7e1ac3495729c2ac33ea_1_samsung-galaxy-s10-1.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:13:36',
            ),
            28 => 
            array (
                'id' => 32,
                'object_type' => 'Direct Invoice',
                'object_id' => 10,
                'uploaded_by' => NULL,
                'file_name' => '063182de423e49e1ca6d9999cd346b32_1_15_v_16_macbookpro_thumb1200_4-3.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:13:56',
            ),
            29 => 
            array (
                'id' => 33,
                'object_type' => 'Direct Invoice',
                'object_id' => 14,
                'uploaded_by' => NULL,
                'file_name' => '0db02672a0d37ceaaf0b48e9c649327f_4_Kelloggs-Corn-Flakes-Real-Mango-300-gm-1014139.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:17:09',
            ),
            30 => 
            array (
                'id' => 34,
                'object_type' => 'Direct Invoice',
                'object_id' => 14,
                'uploaded_by' => NULL,
                'file_name' => '8b937706c041040ee9bc6202a5836121_4_havit.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:17:09',
            ),
            31 => 
            array (
                'id' => 35,
                'object_type' => 'Indirect Order',
                'object_id' => 13,
                'uploaded_by' => NULL,
                'file_name' => '072fc2f3598f35a2e3cb1b863bd06443_1_Kelloggs-Corn-Flakes-Real-Mango-300-gm-1014139.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:17:09',
            ),
            32 => 
            array (
                'id' => 36,
                'object_type' => 'Indirect Order',
                'object_id' => 13,
                'uploaded_by' => NULL,
                'file_name' => '072fc2f3598f35a2e3cb1b863bd06443_1_havit.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:17:09',
            ),
            33 => 
            array (
                'id' => 37,
                'object_type' => 'Direct Invoice',
                'object_id' => 16,
                'uploaded_by' => NULL,
                'file_name' => '2937dd33c6abeb1865297913f70d76ea_2_81n-9sDsr6L._AC_SL1500_.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:18:48',
            ),
            34 => 
            array (
                'id' => 38,
                'object_type' => 'Direct Invoice',
                'object_id' => 16,
                'uploaded_by' => NULL,
                'file_name' => '99b434b53ee98ffb2865d2566439b298_2_huawei-p30-pro.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:18:48',
            ),
            35 => 
            array (
                'id' => 39,
                'object_type' => 'Indirect Order',
                'object_id' => 15,
                'uploaded_by' => NULL,
                'file_name' => '593e5d6e45ee6ea35e7e8ff2a3d7a43b_1_81n-9sDsr6L._AC_SL1500_.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:18:48',
            ),
            36 => 
            array (
                'id' => 40,
                'object_type' => 'Indirect Order',
                'object_id' => 15,
                'uploaded_by' => NULL,
                'file_name' => '593e5d6e45ee6ea35e7e8ff2a3d7a43b_1_huawei-p30-pro.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:18:48',
            ),
            37 => 
            array (
                'id' => 41,
                'object_type' => 'Direct Invoice',
                'object_id' => 18,
                'uploaded_by' => NULL,
                'file_name' => '247c6f4aeea4ccf46a34dc46054f8c3b_5_huawei-p30-pro.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:20:36',
            ),
            38 => 
            array (
                'id' => 42,
                'object_type' => 'Direct Invoice',
                'object_id' => 18,
                'uploaded_by' => NULL,
                'file_name' => '247c6f4aeea4ccf46a34dc46054f8c3b_5_samsung-galaxy-s10-1.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:20:36',
            ),
            39 => 
            array (
                'id' => 43,
                'object_type' => 'Indirect Order',
                'object_id' => 17,
                'uploaded_by' => NULL,
                'file_name' => 'b2779b9ba350f0da24917a8082ffff74_1_huawei-p30-pro.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:20:36',
            ),
            40 => 
            array (
                'id' => 44,
                'object_type' => 'Indirect Order',
                'object_id' => 17,
                'uploaded_by' => NULL,
                'file_name' => 'b2779b9ba350f0da24917a8082ffff74_1_samsung-galaxy-s10-1.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:20:36',
            ),
            41 => 
            array (
                'id' => 45,
                'object_type' => 'Direct Invoice',
                'object_id' => 22,
                'uploaded_by' => NULL,
                'file_name' => '2f7f3778b074a4222fda9655f1d3f1fe_1_iphone-11-pro-select-2019-family.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:23:38',
            ),
            42 => 
            array (
                'id' => 46,
                'object_type' => 'Direct Invoice',
                'object_id' => 22,
                'uploaded_by' => NULL,
                'file_name' => '2f7f3778b074a4222fda9655f1d3f1fe_1_Apple-iPhone-11-1-500x500.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:23:38',
            ),
            43 => 
            array (
                'id' => 47,
                'object_type' => 'Direct Invoice',
                'object_id' => 22,
                'uploaded_by' => NULL,
                'file_name' => 'e517b60c78eea2a830a79e0c5b9eb0eb_1_Kelloggs-Corn-Flakes-Real-Mango-300-gm-1014139.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:23:38',
            ),
            44 => 
            array (
                'id' => 48,
                'object_type' => 'Indirect Order',
                'object_id' => 21,
                'uploaded_by' => NULL,
                'file_name' => '3928ed32b79e9cce546bb5aada520196_1_iphone-11-pro-select-2019-family.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:23:38',
            ),
            45 => 
            array (
                'id' => 49,
                'object_type' => 'Indirect Order',
                'object_id' => 21,
                'uploaded_by' => NULL,
                'file_name' => '3928ed32b79e9cce546bb5aada520196_1_Apple-iPhone-11-1-500x500.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:23:38',
            ),
            46 => 
            array (
                'id' => 50,
                'object_type' => 'Indirect Order',
                'object_id' => 21,
                'uploaded_by' => NULL,
                'file_name' => '3928ed32b79e9cce546bb5aada520196_1_Kelloggs-Corn-Flakes-Real-Mango-300-gm-1014139.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:23:38',
            ),
            47 => 
            array (
                'id' => 51,
                'object_type' => 'Direct Invoice',
                'object_id' => 27,
                'uploaded_by' => NULL,
                'file_name' => '8f5a9a251ce5a27ace84532b8da0c40d_1_corporate-event.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:25:58',
            ),
            48 => 
            array (
                'id' => 52,
                'object_type' => 'Direct Invoice',
                'object_id' => 30,
                'uploaded_by' => NULL,
                'file_name' => '474fac2c5aad41f6a915d23cde7d7039_1_Apple-iPhone-11-1-500x500.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:29:26',
            ),
            49 => 
            array (
                'id' => 53,
                'object_type' => 'Direct Invoice',
                'object_id' => 30,
                'uploaded_by' => NULL,
                'file_name' => '474fac2c5aad41f6a915d23cde7d7039_1_iphone-11-pro-select-2019-family.jpg',
                'original_file_name' => NULL,
                'created_at' => '2020-04-08 18:29:26',
            ),
        ));
        
        
    }
}
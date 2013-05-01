-- Inital Standar Video profile
INSERT INTO `{tbl_prefix}video_profiles` (`profile_id`, `name`, `format`, `ext`, `suffix`, `mobile`, `height`, `width`, `profile_order`, `verify_dimension`, `video_codec`, `audio_codec`, `audio_bitrate`, `video_bitrate`, `audio_rate`, `video_rate`, `resize`, `preset`, `2pass`, `apply_watermark`, `ffmpeg_cmd`, `active`, `date_added`) VALUES
(1, 'MP4', 'mp4', 'mp4', '-480', 'no', 480, 720, 15, 'no', '', '', 396000, 560000, 44100, 25, 'max', 'normal', 'no', 'no', '', 'yes', '0000-00-00 00:00:00');

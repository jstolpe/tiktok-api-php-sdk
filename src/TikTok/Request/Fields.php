<?php

/**
 * Copyright 2024 Justin Stolpe.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace TikTok\Request;

/**
 * Fields
 *
 * Functionality and defines for the TikTok API fields query parameter.
 *
 * @package     tiktok-api-php-sdk
 * @author      Justin Stolpe
 * @link        https://github.com/jstolpe/tiktok-api-php-sdk
 * @license     https://opensource.org/licenses/MIT
 * @version     1.0
 */
class Fields {
	const AVATAR_LARGE_URL = 'avatar_large_url';
	const AVATAR_URL = 'avatar_url';
	const AVATAR_URL_100 = 'avatar_url_100';
	const BIO_DESCRIPTION = 'bio_description';
	const COMMENT_COUNT = 'comment_count';
	const COVER_IMAGE_URL = 'cover_image_url';
	const CREATE_TIME = 'create_time';
	const DISPLAY_NAME = 'display_name';
	const DURATION = 'duration';
	const EMBED_HTML = 'embed_html';
	const EMBED_LINK = 'embed_link';
	const FOLLOWER_COUNT = 'follower_count';
	const FOLLOWING_COUNT = 'following_count'; 
	const HEIGHT = 'height';
	const ID = 'id';
	const IS_VERIFIED = 'is_verified';
	const LIKE_COUNT = 'like_count';
	const LIKES_COUNT = 'likes_count';
	const OPEN_ID = 'open_id';
	const PROFILE_DEEP_LINK = 'profile_deep_link';
	const SHARE_COUNT = 'share_count';
	const SHARE_URL = 'share_url';
	const TITLE = 'title';
	const UNION_ID = 'union_id';
	const VIDEO_COUNT = 'video_count';
	const VIDEO_DESCRIPTION = 'video_description';
	const VIEW_COUNT = 'view_count';
	const WIDTH = 'width';
}

?>
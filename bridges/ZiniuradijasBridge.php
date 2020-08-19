<?php
class ZiniuradijasBridge extends BridgeAbstract {

        const NAME = 'Ziniu Radijas';
        const URI = 'https://www.ziniuradijas.lt/archyvas/';
        const DESCRIPTION = '';
        const MAINTAINER = '';
        const CACHE_TIMEOUT = 900; // 15 minutes

        private $title;

        public function getIcon() {
                return 'https://www.ziniuradijas.lt/themes/ziniuradijas/images/logo_sticky.png';
        }

        public function collectData() {

                $url = $this->getURI();

                $html = getSimpleHTMLDOM($url)
                        or returnServerError('Could not request ' . $url);
                $html = defaultLinkTo($html, $url);

                foreach ($linkHtml->find('.episode p') as $block) {
                            $text = $text . ' ' . $block->outertext;
                        }
                        $timestamp = $this->lithuanianPubDateToTimestamp($article->find('.date', 0)->plaintext . ' ' . $linkHtml->find('.slider-date span', 1)->plaintext);
                        echo $timestamp;
                        $item['timestamp'] = $timestamp;
                        $item['content'] = $text;
                        $item['author'] = trim($linkHtml->find('.speaker span', 0)->plaintext);
                        $item['categories'] = array($article->find('.episode-name a', 0)->plaintext);
                        $item['title'] = $article->find('.title a h3', 0)->plaintext;
                        $item['uri'] = $link;
                        $this->items[] = $item;
                }
        }
        private function lithuanianPubDateToTimestamp($parse) {
                return DateTime::createFromFormat('Y M d H:i',
                            strtr(
                                strtolower(str_replace('m. ', '', strtolower(str_replace(' d.', '', $parse)))),
                                array(
                                        'sausio' => 'jan',
                                        'vasario' => 'feb',
                                        'kovo' => 'march',
                                        'baland  io' => 'apr',
                                        'gegu   ^ws' => 'may',
                                        'bir  elio' => 'jun',
                                        'liepos' => 'jul',
                                        'rugpj   ^mio' => 'aug',
                                        'rugs ^wjo' => 'sep',
                                        'spalio' => 'oct',
                                        'lapkri ^mio' => 'nov',
                                        'gruod  io' => 'dec'
                                )
                            ),
                            new DateTimeZone('Europe/Vilnius')
                        )->getTimestamp();
        }
}
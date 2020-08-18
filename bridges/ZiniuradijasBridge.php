<?php
class ZiniuradijasBridge extends BridgeAbstract {

        const NAME = 'Ziniu Radijas';
        const URI = 'https://www.ziniuradijas.lt/archyvas/';
        const DESCRIPTION = '';
        const MAINTAINER = '';
        const CACHE_TIMEOUT = 900; // 15 minutes

        private $title;

        public function collectData() {

                $url = $this->getURI();

                $html = getSimpleHTMLDOM($url)
                        or returnServerError('Could not request ' . $url);
                $html = defaultLinkTo($html, $url);

                foreach($html->find('.pagination-content .small-new') as $article) {
                        $item = array();
                        $link = $article->find('.title a', 0)->href;
                        $linkHtml = getSimpleHTMLDOM($link)
                                    or returnServerError('Error loading article ' . $link);
                        $item['image'] = 'https://www.ziniuradijas.lt' . $article->find('.block-image img', 0)->getAttribute('data-src');
                        $item['enclosures'] = array('https://www.ziniuradijas.lt' . $linkHtml->find('.download a', 0)->href);
                        $text = '';
                        foreach ($linkHtml->find('.episode p') as $block) {
                            $text = $text . ' ' . $block->innertext;
                        }
                        $item['content'] = $text;
                        $item['author'] = trim($linkHtml->find('.speaker span', 0)->plaintext);
                        $item['categories'] = array($article->find('.episode-name a', 0)->plaintext);
                        $item['title'] = $article->find('.title a h3', 0)->plaintext;
                        $item['uri'] = $link;
                        $this->items[] = $item;
                }
        }
}
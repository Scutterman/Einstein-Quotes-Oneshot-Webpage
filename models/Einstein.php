<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\StringHelper;

/**
 * Einstein is the model behind the Einstein quotes.
 */
class Einstein extends Model
{
    const QUOTE_FILE_LOCATION = '/web/data/Einstein.json';
    const WIKIPEDIA_PAGE_ID = 2;
    const WIKIPEDIA_API_URL = "http://en.wikiquote.org/w/api.php";
    
    public $quotes;
    public $currentQuote;
    public $currentSnippet;
    
    public function init()
    {
        parent::init();
        $this->populateQuotes();
        $this->chooseQuote();
        //$this->refreshQuoteFile();
    }
    
    protected function populateQuotes()
    {
        $this->quotes = json_decode( file_get_contents( Yii::$app->basePath . self::QUOTE_FILE_LOCATION ) );
    }
    
    protected function chooseQuote()
    {
        if ( count( $this->quotes ) == 0 )
        {
            return;
        }
        
        $this->currentQuote = $this->quotes[array_rand($this->quotes)];
        if (isset($_REQUEST['DEBUG'])) { $this->currentQuote = $this->quotes[0]; }
        
        $this->currentSnippet = StringHelper::truncateWords( $this->currentQuote, 6 );
    }
    
    public function refreshQuoteFile()
    {
        $sections = $this->getWikipediaPageSectionsFromPageId( self::WIKIPEDIA_PAGE_ID );
        $quotes = $this->getQuotesFromWikipediaPageSections( self::WIKIPEDIA_PAGE_ID, $sections );
        file_put_contents( Yii::$app->basePath . self::QUOTE_FILE_LOCATION, json_encode( $quotes ) );
        $this->quotes = $quotes;
        $this->chooseQuote();
    }
    
    protected function getWikipediaPageSectionsFromPageId( $pageId )
    {
        $pageId = intval( $pageId );
        $quoteSections = array();
        
        $data = $this->makeRequestToWikipedia( ['action' => 'parse', 'prop' => 'sections', 'pageid' => $pageId ] );
        $sections = $data->parse->sections;
        
        foreach ( $sections as $s ) {
            $splitNum = explode( '.', $s->number );
            if ( count( $splitNum ) > 1 && $splitNum[0] === "1" ) {
                $quoteSections[] = $s->index;
            }
        }
        
        if( count( $quoteSections ) === 0 ) {
          $quoteSections[] = "1";
        }
        return $quoteSections;
    }
    
    protected function getQuotesFromWikipediaPageSections( $pageId, $sections )
    {
        $quotes = array();
        
        foreach ( $sections as $sectionId )
        {
            $data = $this->getQuotesFromWikipediaPageSection( $pageId, $sectionId );
            $quotes = array_merge( $quotes, $data );
        }
        
        return $quotes;
    }
    
    protected function getQuotesFromWikipediaPageSection( $pageId, $sectionId )
    {
        $pageId = intval( $pageId );
        $sectionId = intval( $sectionId );
        $quotes = array();
        
        $data = $this->makeRequestToWikipedia( ['action' => 'parse', 'section' => $sectionId, 'pageid' => $pageId, 'noimages' => '' ] );
        $rawQuotes = $data->parse->text->{"*"};
        
        $DOM = new \DOMDocument;
        $DOM->loadHTML( $rawQuotes );
        
        //get all <li> tags
        $items = $DOM->getElementsByTagName( 'li' );

        for ( $i = 0; $i < $items->length; $i++ )
        {
            $node = $items->item( $i );
            if ( $node->hasChildNodes() && $node->firstChild->nodeName == 'b' ) {
                $quotes[] = utf8_decode( $node->firstChild->nodeValue );
            }
        }
        
        return $quotes;
    }
    
    protected function makeRequestToWikipedia( $requestParameters )
    {
        $requestParameters['format'] = 'json';
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, self::WIKIPEDIA_API_URL );
        
        // API Request parameters
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $requestParameters );
        
        // Wait for the response
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        
        // Execute the request
        $response = curl_exec( $ch );
        
        return json_decode( $response );
    }
        
}

<?php

/**
 * @file
 * Contains Drupal\media_entity_imgur\ImgurApi.
 */

namespace Drupal\media_entity_imgur;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Primary imgur API implementation class.
 *
 * @package Drupal\media_entity_imgur
 */
class ImgurApi {

  /**
   * @var \Drupal\Core\Config\Config
   */
  protected $client_id;

  /**
   * Constructor for the imgur class.
   */
  public function __construct($client_id) {

    $this->client = new Client([
      'base_uri' => 'https://api.imgur.com/3/',
      'headers' => [
        'Authorization' => 'Client-ID ' . $client_id,
      ],
      //'debug' => true
    ]);

  }

  /**
   * Try to find an image or an album using the given parameter.
   *
   * @param string $imageIdOrAlbumId
   *
   * @return array Album (@see https://api.imgur.com/models/album) OR Image (@see https://api.imgur.com/models/image)
   */
  public function albumOrImage($imageIdOrAlbumId){
    try {
      $response = $this->client->get('image/' . $imageIdOrAlbumId);
      $body = $response->getBody();
      return json_decode((string) $body)->data;
    }
    catch (RequestException $e) {
      if ($e->getCode() !== 404) {
        //throw $e;
      }
    }

    try {
      $response = $this->client->get('album/' . $imageIdOrAlbumId);
      $body = $response->getBody();
      return json_decode((string) $body)->data;
    }
    catch (RequestException $e) {
      if ($e->getCode() !== 404) {
        //throw $e;
      }
    }
    //throw new ErrorException('Unable to find an album OR an image with the id, ' . $imageIdOrAlbumId);
  }
}

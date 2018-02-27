<?php
namespace Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer;

use SplSubject;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Generalisation\HandlerRequestInterface;
use Sfynx\CmfBundle\Layers\Infrastructure\EventListener\HandlerRequest\Observer\SfynxCmfEvents;
use Sfynx\AuthBundle\Infrastructure\Event\ViewObject\ResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sfynx\ToolBundle\Util\PiFileManager;

/**
 * Class SeoRedirection
 *
 * Sets the SEO url valule if is a old url.
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class SeoRedirection implements HandlerRequestInterface
{
    /**
     * {@inheritdoc}
     */
    public function update(SplSubject $subject)
    {
        $SEOUrl = false;
        if ( $subject->param->is_switch_redirection_seo_authorized ) {
            $dossier  = $subject->param->seo_redirection_repository . "/old_urls/";
            $fileSeo  = $subject->param->seo_redirection_repository . "/" . $subject->param->seo_redirection_file_name;

            // if all cache seo files are not created from the seo file, we create them.
            $all_cache_files = PiFileManager::GlobFiles($dossier . '*.cache' );
            if (!file_exists($fileSeo) ) {
                PiFileManager::save($fileSeo);
            } elseif (count($all_cache_files) == 0) {
                $subject->cache->getClient()->setPath($dossier);
                $file_handle = fopen($fileSeo, "r");
                while (!feof($file_handle)) {
                    $line = (string) fgets($file_handle);
                    $line_infos = explode('::', $line);
                    if (
                        isset( $line_infos[0]) && !empty( $line_infos[0])
                        &&
                        isset( $line_infos[1]) && !empty( $line_infos[1])
                    ) {
                        $subject->cache->set(str_replace(PHP_EOL, '', $line_infos[0]), str_replace(PHP_EOL, '', $line_infos[1]), 0);
                    }
                }
                fclose($file_handle);
            }
            $filename = $subject->request->getPathInfo();
            $subject->cache->getClient()->setPath($dossier);
            if ($subject->cache->get($filename)) {
                $SEOUrl = $subject->cache->get($filename);
            }
        }
        if ($SEOUrl) {
            // we set response
            $response = new RedirectResponse($SEOUrl, 301);
            // we apply all events allowed to change the redirection response
            $event_response = new ResponseEvent($response, $subject->param->date_expire);
            $subject->dispatcher->dispatch(
                SfynxCmfEvents::HANDLER_REQUEST_CHANGERESPONSE_SEO_REDIRECTION,
                $event_response
            );
            $response = $event_response->getResponse();

            return $response;
            //$response->setResponse(new Response(\Sfynx\ToolBundle\Util\PiFileManager::getCurl($SEOUrl, null, null, $this->request->getUriForPath(''))));
        }

        return false;
    }
}

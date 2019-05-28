<?php

namespace PuphpetBundle\Controller;

use PuphpetBundle\Controller;
use PuphpetBundle\Helper;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NginxController extends Controller
{
    /**
     * @param Request $request
     * @param array   $data
     * @return Response
     * @Route("/extension/nginx",
     *     name="puphpet.nginx.homepage")
     * @Method({"GET"})
     */
    public function indexAction(Request $request, array $data)
    {
        return $this->render('PuphpetBundle::nginx.html.twig', [
            'nginx' => $data,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/extension/nginx/vhost",
     *     name="puphpet.nginx.vhost")
     * @Method({"GET"})
     */
    public function vhostAction(Request $request)
    {
        $data = $this->getExtensionData('nginx');

        return $this->render('PuphpetBundle:nginx:vhost.html.twig', [
            'vhost' => $data['empty_vhost'],
        ]);
    }

    /**
     * @param Request $request
     * @param string  $vhostId
     * @return Response
     * @Route("/extension/nginx/vhost-rewrite/{vhostId}",
     *     name="puphpet.nginx.vhost_rewrite")
     * @Method({"GET"})
     */
    public function vhostRewriteAction(Request $request, string $vhostId)
    {
        $data = $this->getExtensionData('nginx');

        return $this->render('PuphpetBundle:nginx:vhost_rewrite.html.twig', [
            'vhostId' => $vhostId,
            'rewrite' => $data['empty_vhost_rewrite'],
        ]);
    }

    /**
     * @param Request $request
     * @param string  $vhostId
     * @param string  $pregenerated
     * @return Response
     * @Route("/extension/nginx/location/{vhostId}/{pregenerated}",
     *     name="puphpet.nginx.location")
     * @Method({"GET"})
     */
    public function locationAction(Request $request, string $vhostId, string $pregenerated = null)
    {
        $data = $this->getExtensionData('nginx');

        $pregenerated = empty($pregenerated) ? 'html' : $pregenerated;
        $locations    = empty($data['pregenerated_locations'][$pregenerated])
            ? $data['pregenerated_locations']['html']
            : $data['pregenerated_locations'][$pregenerated];

        $randString = Helper\Strings::randString(3);
        $rendered   = [];

        foreach ($locations as $uniqid => $location) {
            $rendered []= $this->renderView('PuphpetBundle:nginx:location.html.twig', [
                'uniqid'   => "{$uniqid}_{$randString}",
                'vhostId'  => $vhostId,
                'location' => $location,
            ]);
        }

        $response = new JsonResponse();
        $response->setContent(json_encode($rendered));

        return $response;
    }

    /**
     * @param Request $request
     * @param string  $vhostId
     * @param string  $locationId
     * @return Response
     * @Route("/extension/nginx/location-rewrite/{vhostId}/{locationId}",
     *     name="puphpet.nginx.location_rewrite")
     * @Method({"GET"})
     */
    public function locationRewriteAction(
        Request $request,
        string $vhostId,
        string $locationId
    ) {
        $data = $this->getExtensionData('nginx');

        return $this->render('PuphpetBundle:nginx:location_rewrite.html.twig', [
            'vhostId'    => $vhostId,
            'locationId' => $locationId,
            'rewrite'    => $data['empty_location_rewrite'],
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/extension/nginx/upstream",
     *     name="puphpet.nginx.upstream")
     * @Method({"GET"})
     */
    public function upstreamAction(Request $request)
    {
        $data = $this->getExtensionData('nginx');

        return $this->render('PuphpetBundle:nginx:upstream.html.twig', [
            'upstream' => $data['empty_upstream'],
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/extension/nginx/proxy",
     *     name="puphpet.nginx.proxy")
     * @Method({"GET"})
     */
    public function proxyAction(Request $request)
    {
        $data = $this->getExtensionData('nginx');

        return $this->render('PuphpetBundle:nginx:proxy.html.twig', [
            'proxy' => $data['empty_proxy'],
        ]);
    }
}

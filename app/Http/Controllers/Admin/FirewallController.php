<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\SucuriFirewall;
use Illuminate\Http\Request;
use Exception;

class FirewallController extends Controller
{
    public function cache()
    {
        return view('panel.backend.pages.firewall.index');
    }

    public function cacheClear()
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://waf.sucuri.net/api?v2',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                // this is the Sucuri CloudProxy API key for this website
                'k' => config('services.sucuri.key'),
                // this is the Sucuri CloudProxy API secret for this website
                's' => config('services.sucuri.secret'),
                // this is the Sucuri CloudProxy API action for this website
                'a' => 'clear_cache'
            )
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $data = json_decode($response, true);
        if($data['status']) {

            return back()->withSuccess($data['messages'][0]);
        } else {
            return back()->withErrors($data['messages'][0]);
        }
    }

    public function ipaddress()
    {
        $ipaddress = SucuriFirewall::all();

        return view('panel.backend.pages.firewall.ipaddress', compact('ipaddress'));
    }

    public function whitelist(Request $request)
    {
        $this->validate($request, [
            'ipaddress' => 'required|ip'
        ]);

        try {
            $data = SucuriFirewall::whitelist($request->ipaddress, true);
            return back()->withSuccess($data['messages'][0]);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function blacklist(Request $request)
    {
        $this->validate($request, [
            'ipaddress' => 'required|ip'
        ]);

        try {
            $data = SucuriFirewall::blacklist($request->ipaddress, true);
            return back()->withSuccess($data['messages'][0]);
        } catch (Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    public function remove(Request $request)
    {
        $this->validate($request, [
            'ipaddress' => 'required|ip|exists:firewall,ip_address'
        ]);

        try {
            if(SucuriFirewall::remove($request->ipaddress)) {
                return back()->withSuccess('IP Address successfully removed.');
            } else {
                return back()->withErrors('IP Address failed to remove.');
            }
        } catch (Exception $e) {

        }
    }
}

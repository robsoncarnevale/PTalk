<?php

namespace App\Http\Controllers;

use App\Models\ClubStore;
use Illuminate\Http\Request;

class ClubStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd("Index ClubStoreController");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd("create ClubStoreController");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        dd("store ClubStoreController");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClubStore  $clubStore
     * @return \Illuminate\Http\Response
     */
    public function show(ClubStore $clubStore)
    {
        dd("show ClubStoreController");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClubStore  $clubStore
     * @return \Illuminate\Http\Response
     */
    public function edit(ClubStore $clubStore)
    {
        dd("edit ClubStoreController");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClubStore  $clubStore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClubStore $clubStore)
    {
        dd("update ClubStoreController");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClubStore  $clubStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClubStore $clubStore)
    {
        dd("destroy ClubStoreController");
    }

    public function adRegistration() {
        dd("adRegistration");
    }

    public function inactiveAds() {
        dd("inactiveAds");
    }

    public function salesHistory() {
        dd("salesHistory");
    }

    public function adverts() {
        dd("adverts");
    }

    public function discountCoupon() {
        dd("discountCoupon");
    }
}

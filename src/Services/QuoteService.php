<?php

namespace VentureDrake\LaravelCrm\Services;

use VentureDrake\LaravelCrm\Models\Quote;
use VentureDrake\LaravelCrm\Models\QuoteProduct;
use VentureDrake\LaravelCrm\Repositories\QuoteRepository;

class QuoteService
{
    /**
     * @var QuoteRepository
     */
    private $quoteRepository;

    /**
     * LeadService constructor.
     * @param QuoteRepository $quoteRepository
     */
    public function __construct(QuoteRepository $quoteRepository)
    {
        $this->quoteRepository = $quoteRepository;
    }

    public function create($request, $person = null, $organisation = null)
    {
        $quote = Quote::create([
            'lead_id' => $request->lead_id ?? null,
            'person_id' => $person->id ?? null,
            'organisation_id' => $organisation->id ?? null,
            'title' => $request->title,
            'description' => $request->description,
            'reference' => $request->reference,
            'currency' => $request->currency,
            'issue_at' => $request->issue_at,
            'expire_at' => $request->expire_at,
            'subtotal' => $request->sub_total,
            'discount' => $request->discount,
            'tax' => $request->tax,
            'adjustments' => $request->adjustment,
            'total' => $request->total,
            'user_owner_id' => $request->user_owner_id,
        ]);

        $quote->labels()->sync($request->labels ?? []);

        if (isset($request->products)) {
            foreach ($request->products as $product) {
                $quote->quoteProducts()->create([
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['unit_price'],
                    'amount' => $product['amount'],
                    'currency' => $request->currency,
                ]);
            }
        }
        
        return $quote;
    }

    public function update($request, Quote $quote, $person = null, $organisation = null)
    {
        $quote->update([
            'person_id' => $person->id ?? null,
            'organisation_id' => $organisation->id ?? null,
            'title' => $request->title,
            'description' => $request->description,
            'reference' => $request->reference,
            'currency' => $request->currency,
            'issue_at' => $request->issue_at,
            'expire_at' => $request->expire_at,
            'subtotal' => $request->sub_total,
            'discount' => $request->discount,
            'tax' => $request->tax,
            'adjustments' => $request->adjustment,
            'total' => $request->total,
            'user_owner_id' => $request->user_owner_id,
        ]);

        $quote->labels()->sync($request->labels ?? []);
        
        if (isset($request->products)) {
            foreach ($request->products as $product) {
                if (isset($product['quote_product_id']) && $quoteProduct = QuoteProduct::find($product['quote_product_id'])) {
                    $quoteProduct->update([
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['unit_price'],
                        'amount' => $product['amount'],
                        'currency' => $request->currency,
                    ]);
                } else {
                    $quote->quoteProducts()->create([
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['unit_price'],
                        'amount' => $product['amount'],
                        'currency' => $request->currency,
                    ]);
                }
            }
        }
        
        return $quote;
    }
}

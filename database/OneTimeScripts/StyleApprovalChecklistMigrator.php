<?php

use Illuminate\Database\Seeder;

class StyleApprovalChecklistMigrator extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lines = \App\Line::all();
        $styles = \App\Style::all();
        $styleDevlopments = \App\StyleDevelopment::get();
        $styleProduction = \App\StyleProduction::get();
        $styleShipped = \App\StyleShipped::get();

        foreach ($lines as $line) {
            foreach ($styles as $key => $style) {
                $this->addStyleDevelopment($style, $line, $styleDevlopments);
                $this->addStyleProduction($style, $line, $styleProduction);
                $this->addStyleShipped($style, $line, $styleShipped);
            }
        }
    }

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleDevelopment($style, $line, $styleDevlopments)
    {
        foreach ($styleDevlopments as $key => $value) {
            $user = $this->getOwner($value->owner, $line);
            $development[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => ($key === 0)
            ];
        }
        \App\Style::find($style->id)->development()->sync($development);
    }

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleProduction($style, $line, $styleProduction)
    {
        foreach ($styleProduction as $key => $value) {
            $user = $this->getOwner($value->owner, $line);
            $production[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => ($key === 0)
            ];
        }
        \App\Style::find($style->id)->production()->sync($production);
    }

    /**
     * @param array $style
     * @param array $line
     */
    public function addStyleShipped($style, $line, $styleShipped)
    {
        foreach ($styleShipped as $key => $value) {
            $user = $this->getOwner($value->owner, $line);
            $shipped[$value->id] = [
                'is_approved' => false,
                'owner' => $user,
                'is_enabled' => (!($key === 3))
            ];
        }
        \App\Style::find($style->id)->shipped()->sync($shipped);
    }

    /**
     * @param  string $owner
     * @return   string
     */
    public function getOwner($owner, $line)
    {
        if($owner == 'PD Lead') {
            $user = \App\User::select('id', 'email', 'display_name')
                ->where('id', '=', $line->product_development_lead_id)
                ->first();
        }
        if($owner == 'Sales Rep') {
            $user = \App\User::select('id', 'email', 'display_name')
                ->where('id', '=', $line->sales_representative_id)
                ->first();
        }
        if($owner == 'Sourcing & Production Lead') {
            $user = \App\User::select('id', 'email', 'display_name')
                ->where('id', '=', $line->production_lead_id)
                ->first();
        }
        return $user;
    }
}
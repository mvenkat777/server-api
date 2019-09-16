<?php

namespace Platform\Address\Repositories\Eloquent;

use Platform\App\Repositories\Eloquent\Repository;
use Platform\Address\Commands\CreateUserAddressCommand;
use Platform\Address\Repositories\Contracts\AddressRepository;

class EloquentAddressRepository extends Repository implements AddressRepository
{
    public function model()
    {
        return 'App\Address';
    }
    /**
     * @param CreateUserAddressCommand $command
     *
     * @return mixed
     */
    public function createAddress($command)
    {
        $address = [
            'label' => $command->label,
            'line1' => $command->line1,
            'line2' => $command->line2,
            'city' => $command->city,
            'state' => $command->state,
            'zip' => $command->zip,
            'country' => $command->country,
            'phone' => $command->phone,
            'is_primary' => $command->isPrimary,
            'air_cargo_port' => $command->airCargoPort,
            'sea_cargo_port' => $command->seaCargoPort
        ];

        return $this->model->create($address);
    }
    /**
     * @param showAddress $command
     *
     * @return all
     */
    public function showAddress($command)
    {
        return $this->model->where('user_id', '=', $command->userId)->get();
    }
    /**
     * @param updateAddress $command
     *
     * @return 1
     */
    public function updateAddress($command)
    {
        return $this->model->where('id', '=', $command->id)->update([
            'label' => $command->label,
            'line1' => $command->line1,
            'line2' => $command->line2,
            'city' => $command->city,
            'state' => $command->state,
            'zip' => $command->zip,
            'country' => $command->country,
            'phone' => $command->phone,
            'is_primary' => $command->isPrimary,
            'air_cargo_port' => $command->airCargoPort,
            'sea_cargo_port' => $command->seaCargoPort
        ]);
    }
    /**
     * @param destroyAddress $command
     *
     * @return all
     */
    public function deleteAddresses($addresses)
    {
        return $this->model->whereIn('id', $addresses)->delete();
    }
    /**
     * @param destroyAllAddress $command
     *
     * @return all
     */
    public function destroyAllAddress($command)
    {
        return $this->model->where('user_id', '=', $command->userId)
                           ->delete();
    }
}

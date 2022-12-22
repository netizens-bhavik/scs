<?php //8555b1c3711b71063fb7160d91f96a0b
/** @noinspection all */

namespace LaravelIdea\Helper\App\Models {

    use App\Models\City;
    use App\Models\Client;
    use App\Models\ContactPerson;
    use App\Models\Country;
    use App\Models\GmLeadsMailList;
    use App\Models\Industry;
    use App\Models\Leads;
    use App\Models\Mom;
    use App\Models\MomJob;
    use App\Models\NotesMaster;
    use App\Models\SystemLog;
    use App\Models\System_logs;
    use App\Models\TempLeads;
    use App\Models\User;
    use Illuminate\Contracts\Support\Arrayable;
    use Illuminate\Database\Query\Expression;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Pagination\Paginator;
    use Illuminate\Support\Collection;
    use LaravelIdea\Helper\_BaseBuilder;
    use LaravelIdea\Helper\_BaseCollection;
    use Spatie\Permission\Contracts\Permission;
    use Spatie\Permission\Contracts\Role;

    /**
     * @method City|null getOrPut($key, $value)
     * @method City|$this shift(int $count = 1)
     * @method City|null firstOrFail($key = null, $operator = null, $value = null)
     * @method City|$this pop(int $count = 1)
     * @method City|null pull($key, $default = null)
     * @method City|null last(callable $callback = null, $default = null)
     * @method City|$this random(int|null $number = null)
     * @method City|null sole($key = null, $operator = null, $value = null)
     * @method City|null get($key, $default = null)
     * @method City|null first(callable $callback = null, $default = null)
     * @method City|null firstWhere(string $key, $operator = null, $value = null)
     * @method City|null find($key, $default = null)
     * @method City[] all()
     */
    class _IH_City_C extends _BaseCollection {
        /**
         * @param int $size
         * @return City[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_City_QB whereId($value)
     * @method _IH_City_QB whereCityName($value)
     * @method _IH_City_QB whereCountryId($value)
     * @method _IH_City_QB whereCreatedBy($value)
     * @method _IH_City_QB whereModifiedBy($value)
     * @method _IH_City_QB whereIsDeleted($value)
     * @method _IH_City_QB whereCreatedAt($value)
     * @method _IH_City_QB whereUpdatedAt($value)
     * @method City baseSole(array|string $columns = ['*'])
     * @method City create(array $attributes = [])
     * @method _IH_City_C|City[] cursor()
     * @method City|null|_IH_City_C|City[] find($id, array $columns = ['*'])
     * @method _IH_City_C|City[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method City|_IH_City_C|City[] findOrFail($id, array $columns = ['*'])
     * @method City|_IH_City_C|City[] findOrNew($id, array $columns = ['*'])
     * @method City first(array|string $columns = ['*'])
     * @method City firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method City firstOrCreate(array $attributes = [], array $values = [])
     * @method City firstOrFail(array $columns = ['*'])
     * @method City firstOrNew(array $attributes = [], array $values = [])
     * @method City firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method City forceCreate(array $attributes)
     * @method _IH_City_C|City[] fromQuery(string $query, array $bindings = [])
     * @method _IH_City_C|City[] get(array|string $columns = ['*'])
     * @method City getModel()
     * @method City[] getModels(array|string $columns = ['*'])
     * @method _IH_City_C|City[] hydrate(array $items)
     * @method City make(array $attributes = [])
     * @method City newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|City[]|_IH_City_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|City[]|_IH_City_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method City sole(array|string $columns = ['*'])
     * @method City updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_City_QB extends _BaseBuilder {}

    /**
     * @method Client|null getOrPut($key, $value)
     * @method Client|$this shift(int $count = 1)
     * @method Client|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Client|$this pop(int $count = 1)
     * @method Client|null pull($key, $default = null)
     * @method Client|null last(callable $callback = null, $default = null)
     * @method Client|$this random(int|null $number = null)
     * @method Client|null sole($key = null, $operator = null, $value = null)
     * @method Client|null get($key, $default = null)
     * @method Client|null first(callable $callback = null, $default = null)
     * @method Client|null firstWhere(string $key, $operator = null, $value = null)
     * @method Client|null find($key, $default = null)
     * @method Client[] all()
     */
    class _IH_Client_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Client[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_Client_QB whereId($value)
     * @method _IH_Client_QB whereLeadId($value)
     * @method _IH_Client_QB whereCompanyName($value)
     * @method _IH_Client_QB whereIndustryId($value)
     * @method _IH_Client_QB whereCountryId($value)
     * @method _IH_Client_QB whereCityId($value)
     * @method _IH_Client_QB whereAddress($value)
     * @method _IH_Client_QB wherePostBoxNo($value)
     * @method _IH_Client_QB wherePhoneNo($value)
     * @method _IH_Client_QB whereEmail($value)
     * @method _IH_Client_QB whereWebsiteName($value)
     * @method _IH_Client_QB whereSortDescription($value)
     * @method _IH_Client_QB whereActiveStatus($value)
     * @method _IH_Client_QB whereManageBy($value)
     * @method _IH_Client_QB whereCreatedBy($value)
     * @method _IH_Client_QB whereModifiedBy($value)
     * @method _IH_Client_QB whereIsDeleted($value)
     * @method _IH_Client_QB whereCreatedAt($value)
     * @method _IH_Client_QB whereUpdatedAt($value)
     * @method Client baseSole(array|string $columns = ['*'])
     * @method Client create(array $attributes = [])
     * @method _IH_Client_C|Client[] cursor()
     * @method Client|null|_IH_Client_C|Client[] find($id, array $columns = ['*'])
     * @method _IH_Client_C|Client[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Client|_IH_Client_C|Client[] findOrFail($id, array $columns = ['*'])
     * @method Client|_IH_Client_C|Client[] findOrNew($id, array $columns = ['*'])
     * @method Client first(array|string $columns = ['*'])
     * @method Client firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Client firstOrCreate(array $attributes = [], array $values = [])
     * @method Client firstOrFail(array $columns = ['*'])
     * @method Client firstOrNew(array $attributes = [], array $values = [])
     * @method Client firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Client forceCreate(array $attributes)
     * @method _IH_Client_C|Client[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Client_C|Client[] get(array|string $columns = ['*'])
     * @method Client getModel()
     * @method Client[] getModels(array|string $columns = ['*'])
     * @method _IH_Client_C|Client[] hydrate(array $items)
     * @method Client make(array $attributes = [])
     * @method Client newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Client[]|_IH_Client_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Client[]|_IH_Client_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Client sole(array|string $columns = ['*'])
     * @method Client updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Client_QB extends _BaseBuilder {}

    /**
     * @method ContactPerson|null getOrPut($key, $value)
     * @method ContactPerson|$this shift(int $count = 1)
     * @method ContactPerson|null firstOrFail($key = null, $operator = null, $value = null)
     * @method ContactPerson|$this pop(int $count = 1)
     * @method ContactPerson|null pull($key, $default = null)
     * @method ContactPerson|null last(callable $callback = null, $default = null)
     * @method ContactPerson|$this random(int|null $number = null)
     * @method ContactPerson|null sole($key = null, $operator = null, $value = null)
     * @method ContactPerson|null get($key, $default = null)
     * @method ContactPerson|null first(callable $callback = null, $default = null)
     * @method ContactPerson|null firstWhere(string $key, $operator = null, $value = null)
     * @method ContactPerson|null find($key, $default = null)
     * @method ContactPerson[] all()
     */
    class _IH_ContactPerson_C extends _BaseCollection {
        /**
         * @param int $size
         * @return ContactPerson[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_ContactPerson_QB whereId($value)
     * @method _IH_ContactPerson_QB whereClientId($value)
     * @method _IH_ContactPerson_QB whereName($value)
     * @method _IH_ContactPerson_QB whereDepartment($value)
     * @method _IH_ContactPerson_QB whereDesignation($value)
     * @method _IH_ContactPerson_QB whereEmail($value)
     * @method _IH_ContactPerson_QB whereMobileNo($value)
     * @method _IH_ContactPerson_QB whereDob($value)
     * @method _IH_ContactPerson_QB whereCreatedBy($value)
     * @method _IH_ContactPerson_QB whereModifiedBy($value)
     * @method _IH_ContactPerson_QB whereIsDeleted($value)
     * @method _IH_ContactPerson_QB whereCreatedAt($value)
     * @method _IH_ContactPerson_QB whereUpdatedAt($value)
     * @method ContactPerson baseSole(array|string $columns = ['*'])
     * @method ContactPerson create(array $attributes = [])
     * @method _IH_ContactPerson_C|ContactPerson[] cursor()
     * @method ContactPerson|null|_IH_ContactPerson_C|ContactPerson[] find($id, array $columns = ['*'])
     * @method _IH_ContactPerson_C|ContactPerson[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method ContactPerson|_IH_ContactPerson_C|ContactPerson[] findOrFail($id, array $columns = ['*'])
     * @method ContactPerson|_IH_ContactPerson_C|ContactPerson[] findOrNew($id, array $columns = ['*'])
     * @method ContactPerson first(array|string $columns = ['*'])
     * @method ContactPerson firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method ContactPerson firstOrCreate(array $attributes = [], array $values = [])
     * @method ContactPerson firstOrFail(array $columns = ['*'])
     * @method ContactPerson firstOrNew(array $attributes = [], array $values = [])
     * @method ContactPerson firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method ContactPerson forceCreate(array $attributes)
     * @method _IH_ContactPerson_C|ContactPerson[] fromQuery(string $query, array $bindings = [])
     * @method _IH_ContactPerson_C|ContactPerson[] get(array|string $columns = ['*'])
     * @method ContactPerson getModel()
     * @method ContactPerson[] getModels(array|string $columns = ['*'])
     * @method _IH_ContactPerson_C|ContactPerson[] hydrate(array $items)
     * @method ContactPerson make(array $attributes = [])
     * @method ContactPerson newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|ContactPerson[]|_IH_ContactPerson_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|ContactPerson[]|_IH_ContactPerson_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method ContactPerson sole(array|string $columns = ['*'])
     * @method ContactPerson updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_ContactPerson_QB extends _BaseBuilder {}

    /**
     * @method Country|null getOrPut($key, $value)
     * @method Country|$this shift(int $count = 1)
     * @method Country|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Country|$this pop(int $count = 1)
     * @method Country|null pull($key, $default = null)
     * @method Country|null last(callable $callback = null, $default = null)
     * @method Country|$this random(int|null $number = null)
     * @method Country|null sole($key = null, $operator = null, $value = null)
     * @method Country|null get($key, $default = null)
     * @method Country|null first(callable $callback = null, $default = null)
     * @method Country|null firstWhere(string $key, $operator = null, $value = null)
     * @method Country|null find($key, $default = null)
     * @method Country[] all()
     */
    class _IH_Country_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Country[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_Country_QB whereId($value)
     * @method _IH_Country_QB whereCountryName($value)
     * @method _IH_Country_QB whereCreatedBy($value)
     * @method _IH_Country_QB whereModifiedBy($value)
     * @method _IH_Country_QB whereIsDeleted($value)
     * @method _IH_Country_QB whereCreatedAt($value)
     * @method _IH_Country_QB whereUpdatedAt($value)
     * @method Country baseSole(array|string $columns = ['*'])
     * @method Country create(array $attributes = [])
     * @method _IH_Country_C|Country[] cursor()
     * @method Country|null|_IH_Country_C|Country[] find($id, array $columns = ['*'])
     * @method _IH_Country_C|Country[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Country|_IH_Country_C|Country[] findOrFail($id, array $columns = ['*'])
     * @method Country|_IH_Country_C|Country[] findOrNew($id, array $columns = ['*'])
     * @method Country first(array|string $columns = ['*'])
     * @method Country firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Country firstOrCreate(array $attributes = [], array $values = [])
     * @method Country firstOrFail(array $columns = ['*'])
     * @method Country firstOrNew(array $attributes = [], array $values = [])
     * @method Country firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Country forceCreate(array $attributes)
     * @method _IH_Country_C|Country[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Country_C|Country[] get(array|string $columns = ['*'])
     * @method Country getModel()
     * @method Country[] getModels(array|string $columns = ['*'])
     * @method _IH_Country_C|Country[] hydrate(array $items)
     * @method Country make(array $attributes = [])
     * @method Country newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Country[]|_IH_Country_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Country[]|_IH_Country_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Country sole(array|string $columns = ['*'])
     * @method Country updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Country_QB extends _BaseBuilder {}

    /**
     * @method GmLeadsMailList|null getOrPut($key, $value)
     * @method GmLeadsMailList|$this shift(int $count = 1)
     * @method GmLeadsMailList|null firstOrFail($key = null, $operator = null, $value = null)
     * @method GmLeadsMailList|$this pop(int $count = 1)
     * @method GmLeadsMailList|null pull($key, $default = null)
     * @method GmLeadsMailList|null last(callable $callback = null, $default = null)
     * @method GmLeadsMailList|$this random(int|null $number = null)
     * @method GmLeadsMailList|null sole($key = null, $operator = null, $value = null)
     * @method GmLeadsMailList|null get($key, $default = null)
     * @method GmLeadsMailList|null first(callable $callback = null, $default = null)
     * @method GmLeadsMailList|null firstWhere(string $key, $operator = null, $value = null)
     * @method GmLeadsMailList|null find($key, $default = null)
     * @method GmLeadsMailList[] all()
     */
    class _IH_GmLeadsMailList_C extends _BaseCollection {
        /**
         * @param int $size
         * @return GmLeadsMailList[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_GmLeadsMailList_QB whereId($value)
     * @method _IH_GmLeadsMailList_QB whereLeadId($value)
     * @method _IH_GmLeadsMailList_QB whereName($value)
     * @method _IH_GmLeadsMailList_QB whereCountry($value)
     * @method _IH_GmLeadsMailList_QB whereIsMailSent($value)
     * @method _IH_GmLeadsMailList_QB whereCreatedAt($value)
     * @method _IH_GmLeadsMailList_QB whereUpdatedAt($value)
     * @method GmLeadsMailList baseSole(array|string $columns = ['*'])
     * @method GmLeadsMailList create(array $attributes = [])
     * @method _IH_GmLeadsMailList_C|GmLeadsMailList[] cursor()
     * @method GmLeadsMailList|null|_IH_GmLeadsMailList_C|GmLeadsMailList[] find($id, array $columns = ['*'])
     * @method _IH_GmLeadsMailList_C|GmLeadsMailList[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method GmLeadsMailList|_IH_GmLeadsMailList_C|GmLeadsMailList[] findOrFail($id, array $columns = ['*'])
     * @method GmLeadsMailList|_IH_GmLeadsMailList_C|GmLeadsMailList[] findOrNew($id, array $columns = ['*'])
     * @method GmLeadsMailList first(array|string $columns = ['*'])
     * @method GmLeadsMailList firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method GmLeadsMailList firstOrCreate(array $attributes = [], array $values = [])
     * @method GmLeadsMailList firstOrFail(array $columns = ['*'])
     * @method GmLeadsMailList firstOrNew(array $attributes = [], array $values = [])
     * @method GmLeadsMailList firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method GmLeadsMailList forceCreate(array $attributes)
     * @method _IH_GmLeadsMailList_C|GmLeadsMailList[] fromQuery(string $query, array $bindings = [])
     * @method _IH_GmLeadsMailList_C|GmLeadsMailList[] get(array|string $columns = ['*'])
     * @method GmLeadsMailList getModel()
     * @method GmLeadsMailList[] getModels(array|string $columns = ['*'])
     * @method _IH_GmLeadsMailList_C|GmLeadsMailList[] hydrate(array $items)
     * @method GmLeadsMailList make(array $attributes = [])
     * @method GmLeadsMailList newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|GmLeadsMailList[]|_IH_GmLeadsMailList_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|GmLeadsMailList[]|_IH_GmLeadsMailList_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method GmLeadsMailList sole(array|string $columns = ['*'])
     * @method GmLeadsMailList updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_GmLeadsMailList_QB extends _BaseBuilder {}

    /**
     * @method Industry|null getOrPut($key, $value)
     * @method Industry|$this shift(int $count = 1)
     * @method Industry|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Industry|$this pop(int $count = 1)
     * @method Industry|null pull($key, $default = null)
     * @method Industry|null last(callable $callback = null, $default = null)
     * @method Industry|$this random(int|null $number = null)
     * @method Industry|null sole($key = null, $operator = null, $value = null)
     * @method Industry|null get($key, $default = null)
     * @method Industry|null first(callable $callback = null, $default = null)
     * @method Industry|null firstWhere(string $key, $operator = null, $value = null)
     * @method Industry|null find($key, $default = null)
     * @method Industry[] all()
     */
    class _IH_Industry_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Industry[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_Industry_QB whereId($value)
     * @method _IH_Industry_QB whereIndustryName($value)
     * @method _IH_Industry_QB whereCreatedBy($value)
     * @method _IH_Industry_QB whereModifiedBy($value)
     * @method _IH_Industry_QB whereIsDeleted($value)
     * @method _IH_Industry_QB whereCreatedAt($value)
     * @method _IH_Industry_QB whereUpdatedAt($value)
     * @method Industry baseSole(array|string $columns = ['*'])
     * @method Industry create(array $attributes = [])
     * @method _IH_Industry_C|Industry[] cursor()
     * @method Industry|null|_IH_Industry_C|Industry[] find($id, array $columns = ['*'])
     * @method _IH_Industry_C|Industry[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Industry|_IH_Industry_C|Industry[] findOrFail($id, array $columns = ['*'])
     * @method Industry|_IH_Industry_C|Industry[] findOrNew($id, array $columns = ['*'])
     * @method Industry first(array|string $columns = ['*'])
     * @method Industry firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Industry firstOrCreate(array $attributes = [], array $values = [])
     * @method Industry firstOrFail(array $columns = ['*'])
     * @method Industry firstOrNew(array $attributes = [], array $values = [])
     * @method Industry firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Industry forceCreate(array $attributes)
     * @method _IH_Industry_C|Industry[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Industry_C|Industry[] get(array|string $columns = ['*'])
     * @method Industry getModel()
     * @method Industry[] getModels(array|string $columns = ['*'])
     * @method _IH_Industry_C|Industry[] hydrate(array $items)
     * @method Industry make(array $attributes = [])
     * @method Industry newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Industry[]|_IH_Industry_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Industry[]|_IH_Industry_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Industry sole(array|string $columns = ['*'])
     * @method Industry updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Industry_QB extends _BaseBuilder {}

    /**
     * @method Leads|null getOrPut($key, $value)
     * @method Leads|$this shift(int $count = 1)
     * @method Leads|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Leads|$this pop(int $count = 1)
     * @method Leads|null pull($key, $default = null)
     * @method Leads|null last(callable $callback = null, $default = null)
     * @method Leads|$this random(int|null $number = null)
     * @method Leads|null sole($key = null, $operator = null, $value = null)
     * @method Leads|null get($key, $default = null)
     * @method Leads|null first(callable $callback = null, $default = null)
     * @method Leads|null firstWhere(string $key, $operator = null, $value = null)
     * @method Leads|null find($key, $default = null)
     * @method Leads[] all()
     */
    class _IH_Leads_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Leads[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_Leads_QB whereId($value)
     * @method _IH_Leads_QB whereSpokenWith($value)
     * @method _IH_Leads_QB whereTempLeadId($value)
     * @method _IH_Leads_QB whereBdmId($value)
     * @method _IH_Leads_QB whereLeadStatus($value)
     * @method _IH_Leads_QB whereContactNo($value)
     * @method _IH_Leads_QB whereEmail($value)
     * @method _IH_Leads_QB whereIsRequirement($value)
     * @method _IH_Leads_QB whereBasicRequirement($value)
     * @method _IH_Leads_QB whereCreatedBy($value)
     * @method _IH_Leads_QB whereModifiedBy($value)
     * @method _IH_Leads_QB whereIsDeleted($value)
     * @method _IH_Leads_QB whereCreatedAt($value)
     * @method _IH_Leads_QB whereUpdatedAt($value)
     * @method Leads baseSole(array|string $columns = ['*'])
     * @method Leads create(array $attributes = [])
     * @method _IH_Leads_C|Leads[] cursor()
     * @method Leads|null|_IH_Leads_C|Leads[] find($id, array $columns = ['*'])
     * @method _IH_Leads_C|Leads[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Leads|_IH_Leads_C|Leads[] findOrFail($id, array $columns = ['*'])
     * @method Leads|_IH_Leads_C|Leads[] findOrNew($id, array $columns = ['*'])
     * @method Leads first(array|string $columns = ['*'])
     * @method Leads firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Leads firstOrCreate(array $attributes = [], array $values = [])
     * @method Leads firstOrFail(array $columns = ['*'])
     * @method Leads firstOrNew(array $attributes = [], array $values = [])
     * @method Leads firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Leads forceCreate(array $attributes)
     * @method _IH_Leads_C|Leads[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Leads_C|Leads[] get(array|string $columns = ['*'])
     * @method Leads getModel()
     * @method Leads[] getModels(array|string $columns = ['*'])
     * @method _IH_Leads_C|Leads[] hydrate(array $items)
     * @method Leads make(array $attributes = [])
     * @method Leads newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Leads[]|_IH_Leads_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Leads[]|_IH_Leads_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Leads sole(array|string $columns = ['*'])
     * @method Leads updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Leads_QB extends _BaseBuilder {}

    /**
     * @method MomJob|null getOrPut($key, $value)
     * @method MomJob|$this shift(int $count = 1)
     * @method MomJob|null firstOrFail($key = null, $operator = null, $value = null)
     * @method MomJob|$this pop(int $count = 1)
     * @method MomJob|null pull($key, $default = null)
     * @method MomJob|null last(callable $callback = null, $default = null)
     * @method MomJob|$this random(int|null $number = null)
     * @method MomJob|null sole($key = null, $operator = null, $value = null)
     * @method MomJob|null get($key, $default = null)
     * @method MomJob|null first(callable $callback = null, $default = null)
     * @method MomJob|null firstWhere(string $key, $operator = null, $value = null)
     * @method MomJob|null find($key, $default = null)
     * @method MomJob[] all()
     */
    class _IH_MomJob_C extends _BaseCollection {
        /**
         * @param int $size
         * @return MomJob[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_MomJob_QB whereId($value)
     * @method _IH_MomJob_QB whereMomId($value)
     * @method _IH_MomJob_QB whereJDate($value)
     * @method _IH_MomJob_QB whereJobCategory($value)
     * @method _IH_MomJob_QB whereQuantity($value)
     * @method _IH_MomJob_QB whereJobDescription($value)
     * @method _IH_MomJob_QB whereJobStatus($value)
     * @method _IH_MomJob_QB whereStatusDate($value)
     * @method _IH_MomJob_QB whereCreatedBy($value)
     * @method _IH_MomJob_QB whereModifiedBy($value)
     * @method _IH_MomJob_QB whereIsDeleted($value)
     * @method _IH_MomJob_QB whereCreatedAt($value)
     * @method _IH_MomJob_QB whereUpdatedAt($value)
     * @method MomJob baseSole(array|string $columns = ['*'])
     * @method MomJob create(array $attributes = [])
     * @method _IH_MomJob_C|MomJob[] cursor()
     * @method MomJob|null|_IH_MomJob_C|MomJob[] find($id, array $columns = ['*'])
     * @method _IH_MomJob_C|MomJob[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method MomJob|_IH_MomJob_C|MomJob[] findOrFail($id, array $columns = ['*'])
     * @method MomJob|_IH_MomJob_C|MomJob[] findOrNew($id, array $columns = ['*'])
     * @method MomJob first(array|string $columns = ['*'])
     * @method MomJob firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method MomJob firstOrCreate(array $attributes = [], array $values = [])
     * @method MomJob firstOrFail(array $columns = ['*'])
     * @method MomJob firstOrNew(array $attributes = [], array $values = [])
     * @method MomJob firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method MomJob forceCreate(array $attributes)
     * @method _IH_MomJob_C|MomJob[] fromQuery(string $query, array $bindings = [])
     * @method _IH_MomJob_C|MomJob[] get(array|string $columns = ['*'])
     * @method MomJob getModel()
     * @method MomJob[] getModels(array|string $columns = ['*'])
     * @method _IH_MomJob_C|MomJob[] hydrate(array $items)
     * @method MomJob make(array $attributes = [])
     * @method MomJob newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|MomJob[]|_IH_MomJob_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|MomJob[]|_IH_MomJob_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method MomJob sole(array|string $columns = ['*'])
     * @method MomJob updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_MomJob_QB extends _BaseBuilder {}

    /**
     * @method Mom|null getOrPut($key, $value)
     * @method Mom|$this shift(int $count = 1)
     * @method Mom|null firstOrFail($key = null, $operator = null, $value = null)
     * @method Mom|$this pop(int $count = 1)
     * @method Mom|null pull($key, $default = null)
     * @method Mom|null last(callable $callback = null, $default = null)
     * @method Mom|$this random(int|null $number = null)
     * @method Mom|null sole($key = null, $operator = null, $value = null)
     * @method Mom|null get($key, $default = null)
     * @method Mom|null first(callable $callback = null, $default = null)
     * @method Mom|null firstWhere(string $key, $operator = null, $value = null)
     * @method Mom|null find($key, $default = null)
     * @method Mom[] all()
     */
    class _IH_Mom_C extends _BaseCollection {
        /**
         * @param int $size
         * @return Mom[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_Mom_QB whereId($value)
     * @method _IH_Mom_QB whereClientId($value)
     * @method _IH_Mom_QB whereMeetingDate($value)
     * @method _IH_Mom_QB whereContactPerson($value)
     * @method _IH_Mom_QB whereMinutesOfMeeting($value)
     * @method _IH_Mom_QB whereBdeFeedback($value)
     * @method _IH_Mom_QB whereMomType($value)
     * @method _IH_Mom_QB whereFollowup($value)
     * @method _IH_Mom_QB whereShareUserId($value)
     * @method _IH_Mom_QB whereNextFollowupDate($value)
     * @method _IH_Mom_QB whereNextFollowupTime($value)
     * @method _IH_Mom_QB whereClientStatus($value)
     * @method _IH_Mom_QB whereCreatedBy($value)
     * @method _IH_Mom_QB whereModifiedBy($value)
     * @method _IH_Mom_QB whereIsDeleted($value)
     * @method _IH_Mom_QB whereCreatedAt($value)
     * @method _IH_Mom_QB whereUpdatedAt($value)
     * @method Mom baseSole(array|string $columns = ['*'])
     * @method Mom create(array $attributes = [])
     * @method _IH_Mom_C|Mom[] cursor()
     * @method Mom|null|_IH_Mom_C|Mom[] find($id, array $columns = ['*'])
     * @method _IH_Mom_C|Mom[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method Mom|_IH_Mom_C|Mom[] findOrFail($id, array $columns = ['*'])
     * @method Mom|_IH_Mom_C|Mom[] findOrNew($id, array $columns = ['*'])
     * @method Mom first(array|string $columns = ['*'])
     * @method Mom firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method Mom firstOrCreate(array $attributes = [], array $values = [])
     * @method Mom firstOrFail(array $columns = ['*'])
     * @method Mom firstOrNew(array $attributes = [], array $values = [])
     * @method Mom firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method Mom forceCreate(array $attributes)
     * @method _IH_Mom_C|Mom[] fromQuery(string $query, array $bindings = [])
     * @method _IH_Mom_C|Mom[] get(array|string $columns = ['*'])
     * @method Mom getModel()
     * @method Mom[] getModels(array|string $columns = ['*'])
     * @method _IH_Mom_C|Mom[] hydrate(array $items)
     * @method Mom make(array $attributes = [])
     * @method Mom newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|Mom[]|_IH_Mom_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|Mom[]|_IH_Mom_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Mom sole(array|string $columns = ['*'])
     * @method Mom updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_Mom_QB extends _BaseBuilder {}

    /**
     * @method NotesMaster|null getOrPut($key, $value)
     * @method NotesMaster|$this shift(int $count = 1)
     * @method NotesMaster|null firstOrFail($key = null, $operator = null, $value = null)
     * @method NotesMaster|$this pop(int $count = 1)
     * @method NotesMaster|null pull($key, $default = null)
     * @method NotesMaster|null last(callable $callback = null, $default = null)
     * @method NotesMaster|$this random(int|null $number = null)
     * @method NotesMaster|null sole($key = null, $operator = null, $value = null)
     * @method NotesMaster|null get($key, $default = null)
     * @method NotesMaster|null first(callable $callback = null, $default = null)
     * @method NotesMaster|null firstWhere(string $key, $operator = null, $value = null)
     * @method NotesMaster|null find($key, $default = null)
     * @method NotesMaster[] all()
     */
    class _IH_NotesMaster_C extends _BaseCollection {
        /**
         * @param int $size
         * @return NotesMaster[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_NotesMaster_QB whereId($value)
     * @method _IH_NotesMaster_QB whereTitle($value)
     * @method _IH_NotesMaster_QB whereDescription($value)
     * @method _IH_NotesMaster_QB whereReminderAt($value)
     * @method _IH_NotesMaster_QB whereManageBy($value)
     * @method _IH_NotesMaster_QB whereCreatedBy($value)
     * @method _IH_NotesMaster_QB whereModifiedBy($value)
     * @method _IH_NotesMaster_QB whereIsDeleted($value)
     * @method _IH_NotesMaster_QB whereCreatedAt($value)
     * @method _IH_NotesMaster_QB whereUpdatedAt($value)
     * @method NotesMaster baseSole(array|string $columns = ['*'])
     * @method NotesMaster create(array $attributes = [])
     * @method _IH_NotesMaster_C|NotesMaster[] cursor()
     * @method NotesMaster|null|_IH_NotesMaster_C|NotesMaster[] find($id, array $columns = ['*'])
     * @method _IH_NotesMaster_C|NotesMaster[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method NotesMaster|_IH_NotesMaster_C|NotesMaster[] findOrFail($id, array $columns = ['*'])
     * @method NotesMaster|_IH_NotesMaster_C|NotesMaster[] findOrNew($id, array $columns = ['*'])
     * @method NotesMaster first(array|string $columns = ['*'])
     * @method NotesMaster firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method NotesMaster firstOrCreate(array $attributes = [], array $values = [])
     * @method NotesMaster firstOrFail(array $columns = ['*'])
     * @method NotesMaster firstOrNew(array $attributes = [], array $values = [])
     * @method NotesMaster firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method NotesMaster forceCreate(array $attributes)
     * @method _IH_NotesMaster_C|NotesMaster[] fromQuery(string $query, array $bindings = [])
     * @method _IH_NotesMaster_C|NotesMaster[] get(array|string $columns = ['*'])
     * @method NotesMaster getModel()
     * @method NotesMaster[] getModels(array|string $columns = ['*'])
     * @method _IH_NotesMaster_C|NotesMaster[] hydrate(array $items)
     * @method NotesMaster make(array $attributes = [])
     * @method NotesMaster newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|NotesMaster[]|_IH_NotesMaster_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|NotesMaster[]|_IH_NotesMaster_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method NotesMaster sole(array|string $columns = ['*'])
     * @method NotesMaster updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_NotesMaster_QB extends _BaseBuilder {}

    /**
     * @method SystemLog|null getOrPut($key, $value)
     * @method SystemLog|$this shift(int $count = 1)
     * @method SystemLog|null firstOrFail($key = null, $operator = null, $value = null)
     * @method SystemLog|$this pop(int $count = 1)
     * @method SystemLog|null pull($key, $default = null)
     * @method SystemLog|null last(callable $callback = null, $default = null)
     * @method SystemLog|$this random(int|null $number = null)
     * @method SystemLog|null sole($key = null, $operator = null, $value = null)
     * @method SystemLog|null get($key, $default = null)
     * @method SystemLog|null first(callable $callback = null, $default = null)
     * @method SystemLog|null firstWhere(string $key, $operator = null, $value = null)
     * @method SystemLog|null find($key, $default = null)
     * @method SystemLog[] all()
     */
    class _IH_SystemLog_C extends _BaseCollection {
        /**
         * @param int $size
         * @return SystemLog[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_SystemLog_QB whereId($value)
     * @method _IH_SystemLog_QB whereUserId($value)
     * @method _IH_SystemLog_QB whereActionId($value)
     * @method _IH_SystemLog_QB whereActionToId($value)
     * @method _IH_SystemLog_QB whereCallType($value)
     * @method _IH_SystemLog_QB whereCallStatus($value)
     * @method _IH_SystemLog_QB whereModule($value)
     * @method _IH_SystemLog_QB whereActionType($value)
     * @method _IH_SystemLog_QB whereDescription($value)
     * @method _IH_SystemLog_QB whereCreatedAt($value)
     * @method _IH_SystemLog_QB whereUpdatedAt($value)
     * @method SystemLog baseSole(array|string $columns = ['*'])
     * @method SystemLog create(array $attributes = [])
     * @method _IH_SystemLog_C|SystemLog[] cursor()
     * @method SystemLog|null|_IH_SystemLog_C|SystemLog[] find($id, array $columns = ['*'])
     * @method _IH_SystemLog_C|SystemLog[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method SystemLog|_IH_SystemLog_C|SystemLog[] findOrFail($id, array $columns = ['*'])
     * @method SystemLog|_IH_SystemLog_C|SystemLog[] findOrNew($id, array $columns = ['*'])
     * @method SystemLog first(array|string $columns = ['*'])
     * @method SystemLog firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method SystemLog firstOrCreate(array $attributes = [], array $values = [])
     * @method SystemLog firstOrFail(array $columns = ['*'])
     * @method SystemLog firstOrNew(array $attributes = [], array $values = [])
     * @method SystemLog firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method SystemLog forceCreate(array $attributes)
     * @method _IH_SystemLog_C|SystemLog[] fromQuery(string $query, array $bindings = [])
     * @method _IH_SystemLog_C|SystemLog[] get(array|string $columns = ['*'])
     * @method SystemLog getModel()
     * @method SystemLog[] getModels(array|string $columns = ['*'])
     * @method _IH_SystemLog_C|SystemLog[] hydrate(array $items)
     * @method SystemLog make(array $attributes = [])
     * @method SystemLog newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|SystemLog[]|_IH_SystemLog_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|SystemLog[]|_IH_SystemLog_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method SystemLog sole(array|string $columns = ['*'])
     * @method SystemLog updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_SystemLog_QB extends _BaseBuilder {}

    /**
     * @method System_logs|null getOrPut($key, $value)
     * @method System_logs|$this shift(int $count = 1)
     * @method System_logs|null firstOrFail($key = null, $operator = null, $value = null)
     * @method System_logs|$this pop(int $count = 1)
     * @method System_logs|null pull($key, $default = null)
     * @method System_logs|null last(callable $callback = null, $default = null)
     * @method System_logs|$this random(int|null $number = null)
     * @method System_logs|null sole($key = null, $operator = null, $value = null)
     * @method System_logs|null get($key, $default = null)
     * @method System_logs|null first(callable $callback = null, $default = null)
     * @method System_logs|null firstWhere(string $key, $operator = null, $value = null)
     * @method System_logs|null find($key, $default = null)
     * @method System_logs[] all()
     */
    class _IH_System_logs_C extends _BaseCollection {
        /**
         * @param int $size
         * @return System_logs[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_System_logs_QB whereId($value)
     * @method _IH_System_logs_QB whereUserId($value)
     * @method _IH_System_logs_QB whereActionId($value)
     * @method _IH_System_logs_QB whereActionToId($value)
     * @method _IH_System_logs_QB whereCallType($value)
     * @method _IH_System_logs_QB whereCallStatus($value)
     * @method _IH_System_logs_QB whereModule($value)
     * @method _IH_System_logs_QB whereActionType($value)
     * @method _IH_System_logs_QB whereDescription($value)
     * @method _IH_System_logs_QB whereCreatedAt($value)
     * @method _IH_System_logs_QB whereUpdatedAt($value)
     * @method System_logs baseSole(array|string $columns = ['*'])
     * @method System_logs create(array $attributes = [])
     * @method _IH_System_logs_C|System_logs[] cursor()
     * @method System_logs|null|_IH_System_logs_C|System_logs[] find($id, array $columns = ['*'])
     * @method _IH_System_logs_C|System_logs[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method System_logs|_IH_System_logs_C|System_logs[] findOrFail($id, array $columns = ['*'])
     * @method System_logs|_IH_System_logs_C|System_logs[] findOrNew($id, array $columns = ['*'])
     * @method System_logs first(array|string $columns = ['*'])
     * @method System_logs firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method System_logs firstOrCreate(array $attributes = [], array $values = [])
     * @method System_logs firstOrFail(array $columns = ['*'])
     * @method System_logs firstOrNew(array $attributes = [], array $values = [])
     * @method System_logs firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method System_logs forceCreate(array $attributes)
     * @method _IH_System_logs_C|System_logs[] fromQuery(string $query, array $bindings = [])
     * @method _IH_System_logs_C|System_logs[] get(array|string $columns = ['*'])
     * @method System_logs getModel()
     * @method System_logs[] getModels(array|string $columns = ['*'])
     * @method _IH_System_logs_C|System_logs[] hydrate(array $items)
     * @method System_logs make(array $attributes = [])
     * @method System_logs newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|System_logs[]|_IH_System_logs_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|System_logs[]|_IH_System_logs_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method System_logs sole(array|string $columns = ['*'])
     * @method System_logs updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_System_logs_QB extends _BaseBuilder {}

    /**
     * @method TempLeads|null getOrPut($key, $value)
     * @method TempLeads|$this shift(int $count = 1)
     * @method TempLeads|null firstOrFail($key = null, $operator = null, $value = null)
     * @method TempLeads|$this pop(int $count = 1)
     * @method TempLeads|null pull($key, $default = null)
     * @method TempLeads|null last(callable $callback = null, $default = null)
     * @method TempLeads|$this random(int|null $number = null)
     * @method TempLeads|null sole($key = null, $operator = null, $value = null)
     * @method TempLeads|null get($key, $default = null)
     * @method TempLeads|null first(callable $callback = null, $default = null)
     * @method TempLeads|null firstWhere(string $key, $operator = null, $value = null)
     * @method TempLeads|null find($key, $default = null)
     * @method TempLeads[] all()
     */
    class _IH_TempLeads_C extends _BaseCollection {
        /**
         * @param int $size
         * @return TempLeads[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_TempLeads_QB whereId($value)
     * @method _IH_TempLeads_QB whereCompanyName($value)
     * @method _IH_TempLeads_QB whereCompanyPhoneNo($value)
     * @method _IH_TempLeads_QB whereCompanyCountryId($value)
     * @method _IH_TempLeads_QB whereCompanyCityId($value)
     * @method _IH_TempLeads_QB whereIndustryId($value)
     * @method _IH_TempLeads_QB whereCompanyEmail($value)
     * @method _IH_TempLeads_QB whereDepartment($value)
     * @method _IH_TempLeads_QB whereDesignation($value)
     * @method _IH_TempLeads_QB whereContactPersonName($value)
     * @method _IH_TempLeads_QB whereContactPersonEmail($value)
     * @method _IH_TempLeads_QB whereContactPersonPhone($value)
     * @method _IH_TempLeads_QB whereDob($value)
     * @method _IH_TempLeads_QB whereAddress($value)
     * @method _IH_TempLeads_QB wherePostBoxNo($value)
     * @method _IH_TempLeads_QB whereCpCountryId($value)
     * @method _IH_TempLeads_QB whereCpCityId($value)
     * @method _IH_TempLeads_QB whereWebsiteName($value)
     * @method _IH_TempLeads_QB whereCallingStatus($value)
     * @method _IH_TempLeads_QB whereRecallingDate($value)
     * @method _IH_TempLeads_QB whereLastCallDate($value)
     * @method _IH_TempLeads_QB whereCallType($value)
     * @method _IH_TempLeads_QB whereTeleCallerId($value)
     * @method _IH_TempLeads_QB whereLastTeleCallerId($value)
     * @method _IH_TempLeads_QB whereIsAssigned($value)
     * @method _IH_TempLeads_QB whereImportedBy($value)
     * @method _IH_TempLeads_QB whereCreatedBy($value)
     * @method _IH_TempLeads_QB whereModifiedBy($value)
     * @method _IH_TempLeads_QB whereIsDeleted($value)
     * @method _IH_TempLeads_QB whereCreatedAt($value)
     * @method _IH_TempLeads_QB whereUpdatedAt($value)
     * @method TempLeads baseSole(array|string $columns = ['*'])
     * @method TempLeads create(array $attributes = [])
     * @method _IH_TempLeads_C|TempLeads[] cursor()
     * @method TempLeads|null|_IH_TempLeads_C|TempLeads[] find($id, array $columns = ['*'])
     * @method _IH_TempLeads_C|TempLeads[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method TempLeads|_IH_TempLeads_C|TempLeads[] findOrFail($id, array $columns = ['*'])
     * @method TempLeads|_IH_TempLeads_C|TempLeads[] findOrNew($id, array $columns = ['*'])
     * @method TempLeads first(array|string $columns = ['*'])
     * @method TempLeads firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method TempLeads firstOrCreate(array $attributes = [], array $values = [])
     * @method TempLeads firstOrFail(array $columns = ['*'])
     * @method TempLeads firstOrNew(array $attributes = [], array $values = [])
     * @method TempLeads firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method TempLeads forceCreate(array $attributes)
     * @method _IH_TempLeads_C|TempLeads[] fromQuery(string $query, array $bindings = [])
     * @method _IH_TempLeads_C|TempLeads[] get(array|string $columns = ['*'])
     * @method TempLeads getModel()
     * @method TempLeads[] getModels(array|string $columns = ['*'])
     * @method _IH_TempLeads_C|TempLeads[] hydrate(array $items)
     * @method TempLeads make(array $attributes = [])
     * @method TempLeads newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|TempLeads[]|_IH_TempLeads_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|TempLeads[]|_IH_TempLeads_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method TempLeads sole(array|string $columns = ['*'])
     * @method TempLeads updateOrCreate(array $attributes, array $values = [])
     */
    class _IH_TempLeads_QB extends _BaseBuilder {}

    /**
     * @method User|null getOrPut($key, $value)
     * @method User|$this shift(int $count = 1)
     * @method User|null firstOrFail($key = null, $operator = null, $value = null)
     * @method User|$this pop(int $count = 1)
     * @method User|null pull($key, $default = null)
     * @method User|null last(callable $callback = null, $default = null)
     * @method User|$this random(int|null $number = null)
     * @method User|null sole($key = null, $operator = null, $value = null)
     * @method User|null get($key, $default = null)
     * @method User|null first(callable $callback = null, $default = null)
     * @method User|null firstWhere(string $key, $operator = null, $value = null)
     * @method User|null find($key, $default = null)
     * @method User[] all()
     */
    class _IH_User_C extends _BaseCollection {
        /**
         * @param int $size
         * @return User[][]
         */
        public function chunk($size)
        {
            return [];
        }
    }

    /**
     * @method _IH_User_QB whereId($value)
     * @method _IH_User_QB whereName($value)
     * @method _IH_User_QB whereEmail($value)
     * @method _IH_User_QB whereEmailVerifiedAt($value)
     * @method _IH_User_QB wherePassword($value)
     * @method _IH_User_QB whereRememberToken($value)
     * @method _IH_User_QB whereCreatedAt($value)
     * @method _IH_User_QB whereUpdatedAt($value)
     * @method _IH_User_QB whereReportingUserId($value)
     * @method _IH_User_QB whereCreatedBy($value)
     * @method _IH_User_QB whereCountryId($value)
     * @method _IH_User_QB whereCityId($value)
     * @method _IH_User_QB whereModifiedBy($value)
     * @method _IH_User_QB whereIsDeleted($value)
     * @method User baseSole(array|string $columns = ['*'])
     * @method User create(array $attributes = [])
     * @method _IH_User_C|User[] cursor()
     * @method User|null|_IH_User_C|User[] find($id, array $columns = ['*'])
     * @method _IH_User_C|User[] findMany(array|Arrayable $ids, array $columns = ['*'])
     * @method User|_IH_User_C|User[] findOrFail($id, array $columns = ['*'])
     * @method User|_IH_User_C|User[] findOrNew($id, array $columns = ['*'])
     * @method User first(array|string $columns = ['*'])
     * @method User firstOr(array|\Closure $columns = ['*'], \Closure $callback = null)
     * @method User firstOrCreate(array $attributes = [], array $values = [])
     * @method User firstOrFail(array $columns = ['*'])
     * @method User firstOrNew(array $attributes = [], array $values = [])
     * @method User firstWhere(array|\Closure|Expression|string $column, $operator = null, $value = null, string $boolean = 'and')
     * @method User forceCreate(array $attributes)
     * @method _IH_User_C|User[] fromQuery(string $query, array $bindings = [])
     * @method _IH_User_C|User[] get(array|string $columns = ['*'])
     * @method User getModel()
     * @method User[] getModels(array|string $columns = ['*'])
     * @method _IH_User_C|User[] hydrate(array $items)
     * @method User make(array $attributes = [])
     * @method User newModelInstance(array $attributes = [])
     * @method LengthAwarePaginator|User[]|_IH_User_C paginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method Paginator|User[]|_IH_User_C simplePaginate(int|null $perPage = null, array $columns = ['*'], string $pageName = 'page', int|null $page = null)
     * @method User sole(array|string $columns = ['*'])
     * @method User updateOrCreate(array $attributes, array $values = [])
     * @method _IH_User_QB permission(array|Collection|int|Permission|string $permissions)
     * @method _IH_User_QB role(array|Collection|int|Role|string $roles, string $guard = null)
     */
    class _IH_User_QB extends _BaseBuilder {}
}

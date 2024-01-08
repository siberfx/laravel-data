<?php

namespace Spatie\LaravelData\Concerns;

use Spatie\LaravelData\Contracts\IncludeableData as IncludeableDataContract;
use Spatie\LaravelData\Contracts\WrappableData as WrappableDataContract;
use Spatie\LaravelData\Support\Partials\Partial;
use Spatie\LaravelData\Support\Partials\PartialsCollection;
use Spatie\LaravelData\Support\Transformation\DataContext;
use Spatie\LaravelData\Support\Wrapping\Wrap;
use Spatie\LaravelData\Support\Wrapping\WrapType;

trait ContextableData
{
    protected ?DataContext $_dataContext = null;

    public function getDataContext(): DataContext
    {
        if ($this->_dataContext === null) {
            $wrap = match (true) {
                method_exists($this, 'defaultWrap') => new Wrap(WrapType::Defined, $this->defaultWrap()),
                default => new Wrap(WrapType::UseGlobal),
            };

            $includedPartials = null;
            $excludedPartials = null;
            $onlyPartials = null;
            $exceptPartials = null;

            if ($this instanceof IncludeableDataContract) {
                if (! empty($this->includeProperties())) {
                    $includedPartials = new PartialsCollection();
                }

                foreach ($this->includeProperties() as $key => $value) {
                    $includedPartials->attach(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }

                if (! empty($this->excludeProperties())) {
                    $excludedPartials = new PartialsCollection();
                }

                foreach ($this->excludeProperties() as $key => $value) {
                    $excludedPartials->attach(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }

                if (! empty($this->onlyProperties())) {
                    $onlyPartials = new PartialsCollection();
                }

                foreach ($this->onlyProperties() as $key => $value) {
                    $onlyPartials->attach(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }

                if (! empty($this->exceptProperties())) {
                    $exceptPartials = new PartialsCollection();
                }

                foreach ($this->exceptProperties() as $key => $value) {
                    $exceptPartials->attach(Partial::fromMethodDefinedKeyAndValue($key, $value));
                }
            }

            return $this->_dataContext = new DataContext(
                $includedPartials,
                $excludedPartials,
                $onlyPartials,
                $exceptPartials,
                $this instanceof WrappableDataContract ? $wrap : new Wrap(WrapType::UseGlobal),
            );
        }

        return $this->_dataContext;
    }
}
